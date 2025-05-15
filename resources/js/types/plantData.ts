// Plant data types for the FloraFinder application

// Conservation status and guide information
export interface ConservationInfo {
  status: string;
  color: string;
  description: string;
  guide: string;
}

// Habitat and ecological information
export interface HabitatInfo {
  name: string;
  description: string;
  climate: string;
}

// IUCN API response structure
export interface IUCNResponse {
  result: Array<{
    category: string;
    population_trend: string;
    marine_system: boolean;
    freshwater_system: boolean;
    terrestrial_system: boolean;
    habitat: string;
    conservation_measures: string[];
  }>;
}

// GBIF API response structure
export interface GBIFResponse {
  key: number;
  kingdom: string;
  phylum: string;
  order: string;
  family: string;
  genus: string;
  species: string;
  kingdomKey: number;
  phylumKey: number;
  classKey: number;
  orderKey: number;
  familyKey: number;
  genusKey: number;
  speciesKey: number;
  habitats?: string[];
  threatStatus?: string;
  establishments?: Array<{ threatStatus?: string }>;
}

// Trefle API response structure (simplified)
export interface TrefleResponse {
  data: Array<{
    id: number;
    common_name: string;
    scientific_name: string;
    family: string;
    genus: string;
    year: number;
    author: string;
    bibliography: string;
    family_common_name: string;
    image_url: string;
    observations: string;
    main_species_id: number;
    vegetable: boolean;
    distributions: {
      native: string[];
      introduced: string[];
    };
    edible: boolean;
    edible_part: string[];
  }>;
}
