
    <script setup>
    import { ref } from 'vue';


    const lat1 = ref(-6.175650241214587); // Contoh koordinat JP
    const lon1 = ref(106.82039980262066); // Contoh koordinat JP
    //const lat2 = ref(-6.175671183417352); // Contoh koordinat Home
    //const lon2 = ref(106.82037580577096); // Contoh koordinat Home
    // const lat2 = ref( -6.151925698212058); // Contoh koordinat Home
    // const lon2 = ref(106.81515701545916); // Contoh koordinat Home

    // console.log(lat2.value, lon2.value);

    const getCoordinates = () => {
        navigator.geolocation.getCurrentPosition(
                position => {
                const lat2 = ref(position.coords.latitude); //klo dari dalem sini gabisa cok
                const lon2 = ref(position.coords.longitude);
                // console.log(position.coords.latitude);
            },
        );
    }
    // lat2.value = -6.020202 //kalo dari sini bisa ajg;
    getCoordinates();

    console.log(lat2.value, lon2.value,);


    const haver = async(lat1,lat2,lon1,lon2) => {

        // console.log(lat1, lat2, lon1, lon2);
        const R = 6371; // Radius of the Earth in kilometers
        const toRad = angle => angle * (Math.PI / 180); // Convert degree to radians

        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);

        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        const distanceInKm = R * c; // Distance in kilometers

        const distanceInMeters = distanceInKm * 1000; // Convert to meters

        console.log(parseFloat(distanceInMeters.toFixed(1)));

        return parseFloat(distanceInMeters.toFixed(1));
    }

    haver(lat1.value, lat2.value, lon1.value, lon2.value);
    </script>

    <template>
        <p>JP: -6.175650241214587, 106.82039980262066</p>
        <p>samping JP: -6.175672692306563, 106.8204410667335</p>
        <p>Home: -6.151925698212058, 106.81515701545916</p>
        <p style="color: red;">dudung gaming</p>
    </template>
