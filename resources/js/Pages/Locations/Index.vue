<script setup>
    import axios from 'axios';
    import { ref, onMounted, onUnmounted } from 'vue';

    const video = ref(null);
    const capturedImage = ref(null);
    const lat1 = ref(-6.175658980663761); // Contoh koordinat JP
    const lon1 = ref(106.82039509866516); // Contoh koordinat JP
    const lat2 = ref(-6.175672692306563); // Contoh koordinat sJP
    const lon2 = ref(106.8204410667335); // Contoh koordinat sjP
    const jarak = ref(null); // Contoh koordinat sjP
    const error = ref('');

    const startCamera = async () => {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.value.srcObject = stream;
    } catch (error) {
        console.error("Error accessing camera:", error);
    }
    };

    const captureImage = () => {
    const canvas = document.createElement("canvas");
    canvas.width = video.value.videoWidth;
    canvas.height = video.value.videoHeight;
    const context = canvas.getContext("2d");
    context.drawImage(video.value, 0, 0, canvas.width, canvas.height);
    capturedImage.value = canvas.toDataURL("image/png");
    };

    const getCoordinates = () => {
        navigator.geolocation.getCurrentPosition(
                position => {
                lat2.value = position.coords.latitude; //Udah bisa :D
                lon2.value = position.coords.longitude;
                // console.log(position.coords.latitude, position.coords.longitude);
            },
        );
    }

    const haver = async(lat1,lat2,lon1,lon2) => {

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

        // console.log(parseFloat(distanceInMeters.toFixed(1)));
        // console.log(distanceInKm);

        return parseFloat(distanceInMeters.toFixed(1));
    }

    setTimeout(() => {
        haver(lat1.value, lat2.value, lon1.value, lon2.value);
    }, 1000);

    //setInterval(getCoordinates, 10000); //Buat update koordinat tiap 10 detik

    const foto = async () => {
        // let jarak =  await haver(lat1.value, lat2.value, lon1.value, lon2.value);
        // console.log(jarak);
        axios.post('/location', {
            jarak: jarak.value,
        }).then(response => {
            console.log(response.data);
        }).catch(error => {
            console.log(error.response.data);
        })
    }

    onMounted( () => {
        getCoordinates();
        console.log(lat1.value, lat2.value, lon1.value, lon2.value);

    setTimeout(async () => {
        jarak.value = await haver(lat1.value, lat2.value, lon1.value, lon2.value);
    }, 1000);

        startCamera();
    })

    onUnmounted(() => {
        const stream = video.value.srcObject;
        const tracks = stream.getTracks();
        tracks.forEach(track => track.stop());
    });
    </script>

    <template>
        <div>
            <video ref="video" width="300" autoplay></video>
            <button @click="captureImage">Capture Image</button>
            <div v-if="capturedImage">
                <p>Captured Image:</p>
                <img :src="capturedImage" alt="Captured Image" width="300" />
            </div>
        </div>
        <p>Jarak ke sekolah: {{ jarak }} Meter</p>

        <button type="button" @click="foto">Submit</button>
    </template>
