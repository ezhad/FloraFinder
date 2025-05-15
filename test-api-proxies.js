const axios = require('axios');

// Test plant names
const testPlants = [
  'Hibiscus rosa-sinensis', // Hibiscus
  'Quercus robur',         // Oak
  'Pinus sylvestris'       // Pine
];

// Function to test IUCN proxy
async function testIUCNProxy(plantName) {
  try {
    console.log(`Testing IUCN proxy with plant: ${plantName}`);
    const response = await axios.get(`http://florafinder.test/api/proxy/iucn?name=${encodeURIComponent(plantName)}`);
    console.log('IUCN Response Status:', response.status);
    console.log('IUCN Response Data:', JSON.stringify(response.data, null, 2));
    return response.data;
  } catch (error) {
    console.error('IUCN API Error:', error.message);
    if (error.response) {
      console.error('Error Status:', error.response.status);
      console.error('Error Data:', error.response.data);
    }
    return null;
  }
}

// Function to test Trefle proxy
async function testTrefleProxy(plantName) {
  try {
    console.log(`Testing Trefle proxy with plant: ${plantName}`);
    const response = await axios.get(`http://florafinder.test/api/proxy/trefle?name=${encodeURIComponent(plantName)}`);
    console.log('Trefle Response Status:', response.status);
    console.log('Trefle Response Data:', JSON.stringify(response.data, null, 2));
    return response.data;
  } catch (error) {
    console.error('Trefle API Error:', error.message);
    if (error.response) {
      console.error('Error Status:', error.response.status);
      console.error('Error Data:', error.response.data);
    }
    return null;
  }
}

// Run the tests
async function runTests() {
  for (const plant of testPlants) {
    console.log('\n==============================');
    console.log(`Testing plant: ${plant}`);
    console.log('==============================\n');

    await testIUCNProxy(plant);
    console.log('\n------------------------------\n');
    await testTrefleProxy(plant);
    console.log('\n==============================\n');
  }
}

runTests().catch(console.error);
