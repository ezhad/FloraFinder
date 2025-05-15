import axios from 'axios';

// Define the response types
interface IUCNResponse {
  // The actual IUCN API response is different from what we initially expected
  // Based on IUCN Red List API documentation
  name: string;
  result?: Array<{
    taxonid: number;
    scientific_name: string;
    kingdom: string;
    phylum: string;
    class: string;
    order: string;
    family: string;
    genus: string;
    category: string;  // Conservation status (e.g., "LC", "EN", "CR")
    main_common_name?: string;
    population_trend?: string;
    marine_system?: boolean;
    freshwater_system?: boolean;
    terrestrial_system?: boolean;
    assessor?: string;
    assessment_date?: string;
    criteria?: string;
    population?: string;
    habitat?: string;
    conservation_measures?: string[];
  }>;
  count?: number;
  error?: string;
}

interface GBIFResponse {
  key?: number;
  nubKey?: number;
  nameKey?: number;
  taxonID?: string;
  sourceTaxonKey?: number;
  kingdom?: string;
  phylum?: string;
  order?: string;
  family?: string;
  genus?: string;
  species?: string;
  kingdomKey?: number;
  phylumKey?: number;
  classKey?: number;
  orderKey?: number;
  familyKey?: number;
  genusKey?: number;
  speciesKey?: number;
  datasetKey?: string;
  constituentKey?: string;
  parentKey?: number;
  parent?: string;
  scientificName?: string;
  canonicalName?: string;
  vernacularName?: string;
  authorship?: string;
  nameType?: string;
  rank?: string;
  origin?: string;
  taxonomicStatus?: string;
  nomenclaturalStatus?: string;
  remarks?: string;
  numDescendants?: number;
  lastCrawled?: string;
  lastInterpreted?: string;
  issues?: string[];
  synonym?: boolean;
  class?: string;
  habitats?: string[];
  threatStatus?: string;
  establishments?: Array<{ threatStatus?: string }>;
}

interface ConservationInfo {
  status: string;
  color: string;
  description: string;
  guide: string;
}

interface HabitatInfo {
  name: string;
  description: string;
  climate: string;
}

// API Configuration
// Note: In a production environment, these should be environment variables

/**
 * Fetch conservation information from IUCN API
 * @param scientificName - The scientific name of the plant
 * @param iucnId - Optional IUCN ID for the species
 */
export async function fetchConservationInfo(scientificName: string, iucnId?: string): Promise<ConservationInfo | null> {
  try {
    // Initialize with default conservation info
    const conservationInfo: ConservationInfo = {
      status: 'Unknown',
      color: 'gray-500',
      description: 'Conservation status not yet evaluated',
      guide: 'Practice general conservation principles: maintain natural habitat, avoid overharvesting, and support local biodiversity.'
    };

    // Define which identifier to use
    const searchTerm = iucnId || scientificName;
    
    if (!searchTerm) {
      console.warn('No valid search term provided for conservation data');
      return conservationInfo;
    }
    
    console.log(`Fetching conservation data for: ${searchTerm}`);
    
    // Call the proxy API endpoint      const response = await axios.get(`/api/proxy/iucn?name=${encodeURIComponent(searchTerm)}`);
    console.log('IUCN API response:', response.data);
    
    // Check if the response has an error property
    if (response.data?.error) {
      console.error('IUCN API returned an error:', response.data.error);
      return conservationInfo;
    }
    
    // Handle different API response formats
    let data: IUCNResponse;
    
    if (typeof response.data === 'string') {
      try {
        // Sometimes the API might return a string that needs parsing
        data = JSON.parse(response.data);
      } catch (parseError) {
        console.error('Error parsing IUCN API response:', parseError);
        return conservationInfo;
      }
    } else {
      // Otherwise, use the response data directly
      data = response.data as IUCNResponse;
    }

    // Check if data has results and is not empty
    if (data?.result && data.result.length > 0) {
      const result = data.result[0];
      
      // Update conservation info from the API response
      if (result.category) {
        conservationInfo.status = result.category;
        conservationInfo.description = getStatusDescription(result.category);
        conservationInfo.color = getStatusColor(result.category);
        
        // Add conservation measures if available
        if (result.conservation_measures && result.conservation_measures.length > 0) {
          conservationInfo.guide = result.conservation_measures.join(' ');
        } else {
          conservationInfo.guide = getDefaultGuide(result.category);
        }
      }
    } else {
      console.log('No conservation data found for', searchTerm);
    }

    console.log('Returning conservation info:', conservationInfo);
    return conservationInfo;
  } catch (error) {
    console.error('Error fetching conservation data:', error);
    
    // Log more detailed error information
    if (axios.isAxiosError(error)) {
      console.error('API Error Details:', {
        status: error.response?.status,
        data: error.response?.data,
        headers: error.response?.headers
      });
    }
    
    // Return fallback data in case of error
    return {
      status: 'Unknown',
      color: 'gray-500',
      description: 'Information not available',
      guide: 'Practice general conservation principles: maintain natural habitat, avoid overharvesting, and support local biodiversity.'
    };
  }
}

/**
 * Fetch habitat information from GBIF and other sources
 * @param scientificName - The scientific name of the plant
 * @param gbifId - Optional GBIF ID for the plant species
 */
export async function fetchHabitatInfo(scientificName: string, gbifId?: string): Promise<HabitatInfo | null> {
  try {
    console.log(`Fetching habitat info for: ${scientificName}, GBIF ID: ${gbifId || 'not provided'}`);
    
    // Initialize with default habitat info
    const habitatInfo: HabitatInfo = {
      name: 'Various',
      description: 'Information not available for this species',
      climate: 'Various'
    };

    // If GBIF ID is available, use that for more accurate data
    if (gbifId) {
      try {
        console.log(`Fetching from GBIF API using ID: ${gbifId}`);
        const response = await axios.get(`https://api.gbif.org/v1/species/${gbifId}`);
        console.log('GBIF API response by ID:', response.data);
        
        const data = response.data as GBIFResponse;

        if (data) {
          // Some GBIF records include habitat information
          if (data.habitats && data.habitats.length > 0) {
            habitatInfo.name = data.habitats.join(', ');
            habitatInfo.description = `This species is found in ${data.habitats.join(', ')} habitats.`;
          }

          // Try to determine climate from taxonomic information
          const climate = determineClimateFromTaxonomy(data);
          if (climate) {
            habitatInfo.climate = climate;
          }
        }
      } catch (gbifError) {
        console.error('Error fetching data from GBIF by ID:', gbifError);
      }
    } else {
      // Try Trefle API first
      try {
        console.log(`Fetching from Trefle API using name: ${scientificName}`);
        const response = await axios.get(
          `/api/proxy/trefle?name=${encodeURIComponent(scientificName)}`
        );
        console.log('Trefle API response:', response.data);

        if (response.data?.data && response.data.data.length > 0) {
          const plant = response.data.data[0];
          console.log('Found plant in Trefle:', plant);

          // Trefle provides distribution info which can help determine habitat
          if (plant.distributions && plant.distributions.native && plant.distributions.native.length > 0) {
            const regions = plant.distributions.native;
            habitatInfo.name = deriveHabitatFromRegions(regions);
            habitatInfo.description = `Native to ${regions.join(', ')}`;
            habitatInfo.climate = deriveClimateFromRegions(regions);
          }
        }
      } catch (trefleError) {
        console.error('Error fetching data from Trefle:', trefleError);
      }

      // Use GBIF's species API as fallback
      try {
        console.log(`Fetching from GBIF API using name: ${scientificName}`);
        const gbifSearchResponse = await axios.get(
          `https://api.gbif.org/v1/species/match?name=${encodeURIComponent(scientificName)}`
        );
        console.log('GBIF match API response:', gbifSearchResponse.data);

        if (gbifSearchResponse.data && gbifSearchResponse.data.usageKey) {
          const speciesKey = gbifSearchResponse.data.usageKey;
          console.log(`Found GBIF species key: ${speciesKey}`);
          
          const speciesResponse = await axios.get(`https://api.gbif.org/v1/species/${speciesKey}`);
          console.log('GBIF species API response:', speciesResponse.data);
          
          const data = speciesResponse.data as GBIFResponse;

          if (data.habitats && data.habitats.length > 0) {
            habitatInfo.name = data.habitats.join(', ');
            habitatInfo.description = `This species is found in ${data.habitats.join(', ')} habitats.`;
          }
        }
      } catch (gbifError) {
        console.error('Error fetching data from GBIF by name:', gbifError);
      }
    }

    console.log('Returning habitat info:', habitatInfo);
    return habitatInfo;
  } catch (error) {
    console.error('Error fetching habitat data:', error);
    
    // Log more detailed error information
    if (axios.isAxiosError(error)) {
      console.error('API Error Details:', {
        status: error.response?.status,
        data: error.response?.data,
        headers: error.response?.headers
      });
    }
    
    // Return fallback data
    return {
      name: 'Various',
      description: 'Information not available for this species',
      climate: 'Various'
    };
  }
}

// Helper functions
function getStatusDescription(status: string): string {
  switch (status.toLowerCase()) {
    case 'ex':
    case 'extinct':
      return 'This species is no longer found on Earth';
    case 'ew':
    case 'extinct in the wild':
      return 'Only surviving in captivity or cultivation';
    case 'cr':
    case 'critically endangered':
      return 'Extremely high risk of extinction in the wild';
    case 'en':
    case 'endangered':
      return 'High risk of extinction in the wild';
    case 'vu':
    case 'vulnerable':
      return 'High risk of endangerment in the wild';
    case 'nt':
    case 'near threatened':
      return 'Likely to become endangered in the near future';
    case 'lc':
    case 'least concern':
      return 'Widespread and abundant';
    case 'dd':
    case 'data deficient':
      return 'Not enough data to determine risk level';
    case 'ne':
    case 'not evaluated':
    default:
      return 'Conservation status not yet evaluated';
  }
}

function getStatusColor(status: string): string {
  switch (status.toLowerCase()) {
    case 'ex':
    case 'extinct':
      return 'black';
    case 'ew':
    case 'extinct in the wild':
      return 'red-900';
    case 'cr':
    case 'critically endangered':
      return 'red-600';
    case 'en':
    case 'endangered':
      return 'red-500';
    case 'vu':
    case 'vulnerable':
      return 'orange-500';
    case 'nt':
    case 'near threatened':
      return 'yellow-600';
    case 'lc':
    case 'least concern':
      return 'green-600';
    case 'dd':
    case 'data deficient':
      return 'blue-500';
    case 'ne':
    case 'not evaluated':
    default:
      return 'gray-500';
  }
}

function getDefaultGuide(status: string): string {
  switch (status.toLowerCase()) {
    case 'ex':
    case 'extinct':
      return 'Record historical information and analyze causes to prevent similar extinctions.';
    case 'ew':
    case 'extinct in the wild':
      return 'Support captive breeding programs and habitat restoration efforts.';
    case 'cr':
    case 'critically endangered':
      return 'Urgent conservation action needed. Report sightings, support protected areas, and avoid disturbing habitat.';
    case 'en':
    case 'endangered':
      return 'Support conservation programs, avoid collection, and promote habitat protection.';
    case 'vu':
    case 'vulnerable':
      return 'Practice sustainable interactions, promote suitable habitat, and support monitoring programs.';
    case 'nt':
    case 'near threatened':
      return 'Monitor populations, maintain habitat, and follow sustainable practices.';
    case 'lc':
    case 'least concern':
      return 'Continue sustainable practices and monitor for changes in population.';
    case 'dd':
    case 'data deficient':
      return 'Support research and documentation efforts to better understand this species.';
    case 'ne':
    case 'not evaluated':
    default:
      return 'Practice general conservation principles: maintain natural habitat, avoid overharvesting, and support local biodiversity.';
  }
}

function determineClimateFromTaxonomy(data: GBIFResponse): string {
  // A very basic implementation - in a real app, you'd use a more sophisticated approach
  if (!data.family) return 'Various';

  // Some basic family-to-climate mappings (extremely simplified)
  const familyToClimate: Record<string, string> = {
    'Cactaceae': 'Arid',
    'Pinaceae': 'Temperate to Boreal',
    'Arecaceae': 'Tropical to Subtropical',
    'Fagaceae': 'Temperate',
    'Ericaceae': 'Temperate to Arctic',
    'Poaceae': 'Various (worldwide)'
  };

  return familyToClimate[data.family] || 'Various';
}

function deriveHabitatFromRegions(regions: string[]): string {
  // This is a simplified approach - in a real app, you'd use more detailed logic
  const regionHabitats: Record<string, string[]> = {
    'North America': ['Forests', 'Prairies', 'Mountains'],
    'Europe': ['Deciduous Forests', 'Grasslands', 'Mediterranean'],
    'Asia': ['Tropical Forests', 'Temperate Forests', 'Mountains'],
    'South America': ['Rainforests', 'Mountains', 'Grasslands'],
    'Africa': ['Savannas', 'Deserts', 'Rainforests'],
    'Australia': ['Deserts', 'Mediterranean Scrub', 'Forests']
  };

  const habitats = new Set<string>();

  regions.forEach(region => {
    const mainRegion = Object.keys(regionHabitats).find(r => region.includes(r));
    if (mainRegion && regionHabitats[mainRegion]) {
      regionHabitats[mainRegion].forEach(h => habitats.add(h));
    }
  });

  return habitats.size > 0 ? Array.from(habitats).join(', ') : 'Various';
}

function deriveClimateFromRegions(regions: string[]): string {
  // Simplified climate determination
  const regionClimates: Record<string, string[]> = {
    'North America': ['Temperate', 'Continental'],
    'Europe': ['Temperate', 'Mediterranean'],
    'Asia': ['Temperate', 'Tropical', 'Continental'],
    'South America': ['Tropical', 'Temperate'],
    'Africa': ['Tropical', 'Arid', 'Mediterranean'],
    'Australia': ['Arid', 'Mediterranean', 'Tropical']
  };

  const climates = new Set<string>();

  regions.forEach(region => {
    const mainRegion = Object.keys(regionClimates).find(r => region.includes(r));
    if (mainRegion && regionClimates[mainRegion]) {
      regionClimates[mainRegion].forEach(c => climates.add(c));
    }
  });

  return climates.size > 0 ? Array.from(climates).join(', ') : 'Various';
}
