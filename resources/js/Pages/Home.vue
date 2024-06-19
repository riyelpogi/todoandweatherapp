
<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { ref, defineProps, reactive } from 'vue';

const props = defineProps({
    todos:Object
});


const results = ref([]);
const obj = reactive({
    id:null
})

const todo = ref(true);
const weather = ref(false);
const isTodoActive = ref("bg-green-600");
const isWeatherActive = ref("bg-blue-600");
const showModal = ref(false);

const form = useForm({
    id:null,
    title:null,
    subject:null
});

const weatherForm = useForm({
    address:null
});

const notice = ref("0");
   
const subjectInput = () => {
    notice.value = form.subject.length;
}

const submitTodo = () => {
    if(form.subject == null || form.subject.length < 0){
        form.errors.subject = 'Subject field is required.';
    }else{
        form.post('/upload/todo',{
            preserveScroll: true,
            onSuccess: () => form.reset()
        });
    }
 
}


const fetchData = (arg) => {

    fetch('/api/weather?arg='+arg)
        .then(response => response.json())
        .then(data => {
            results.value = data.timelines.daily;
            console.log(results.value)
        })
}

// fetchData();

const fetchPlaces = (arg) => {
    let lat = "";
    let lng = "";
    // console.log(arg);
    fetch("/api/places?address="+arg)
        .then(response => response.json())
        .then(data => {
            data.result.forEach(element => {
                lat = element.geometry.location.lat;
                lng = element.geometry.location.lng;
            });

            fetchData(lat +","+lng);
        })
}

// fetchPlaces();

const show = (id, title, subject) => {
    window.scrollTo({ top: 0,  behavior: 'smooth' });
    showModal.value = true;
    form.id = id;
    form.title = title;
    form.subject = subject;
    notice.value = form.subject.length;
}

const deleteTodo = (id) => {
    if(id != null){
        router.delete('/delete/todo/'+id, {
            preserveScroll: true
        });
    }
}

const showWeather = () => {
    weather.value = true;
    todo.value = false;

}

const showTodo = () => {
    weather.value = false;
    todo.value = true;

}


const submitAddress = () => {
    // alert(weatherForm.address)
    if(weatherForm.address != null){
        fetchPlaces(weatherForm.address);
    }
}
</script>


<template>
    <div class="w-full relative flex items-center flex-col h-screen">
        <div class="w-6/12 relative flex mt-10 gap-5">
            <button class="p-2 relative rounded-lg  font-bold text-white uppercase " :class="[ todo == true ? 'bg-green-600' : 'bg-blue-600']" @click="showTodo">todo</button>
            <button class="p-2 relative rounded-lg  font-bold text-white uppercase" :class="[ weather == true ? 'bg-green-600' : 'bg-blue-600']" @click="showWeather">Weather</button>
        </div>
        <div class="bg-gray-800 w-6/12 rounded relative flex flex-col justify-center items-center p-5 mt-10" v-if="todo">
            <div class="w-11/12 relative">
                <h1 class="text-white font-bold text-4xl">TODO APPLICATION:</h1>
                <span class="text-white font-bold">Organize Life, One Task at a Time with Our Todo App!</span>
            </div>
            <div class="w-11/12 relative flex flex-col">
                <form @submit.prevent="submitTodo"> 
                    <div class="flex flex-col mt-5">
                        <input type="text" name="title"  id="title" v-model="form.title" class="p-2 rounded text-lg font-bold"  placeholder="Title">
                    </div>
                    <div class="flex flex-col mt-5">
                        <textarea name="" id="" cols="30" @input="subjectInput" rows="10" v-model="form.subject" class="p-5 rounded" placeholder="Type something here..."></textarea>
                        <div class="flex justify-between">
                            <span class="text-xs text-white">Subject length {{ notice }} / 1000</span>
                            <span class="text-xs text-white">{{ form.errors.subject }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col mt-8">    
                        <button class="uppercase bg-blue-800 rounded font-bold p-2 text-white hover:bg-blue-600" type="submit">submit</button>
                    </div>  
                </form>
            </div>
        </div>

        <div class="bg-gray-800 w-6/12 rounded relative flex flex-col justify-center items-center p-5 mt-10 mb-10" v-if="weather">
            <div class="w-11/12 relative m-5 flex gap-5 ">
               <form @submit.prevent="submitAddress" class="flex justify-center w-11/12 gap-5">
                <input type="text" class="w-11/12 rounded-lg p-4" placeholder="Enter address" v-model="weatherForm.address">
                <button class="p-2 rounded-lg submit bg-green-600 font-bold text-white"  type="submit" :disabled="weatherForm.processing">SUBMIT</button>
               </form>
            </div>
            <div class="w-11/12 relative m-5 flex gap-5 flex-col">
                <div class="w-11/12 relative flex flex-col bg-yellow-600 rounded-lg p-2" v-for="(result, index) in results" :key="index">
                    <h1 class="text-white font-bold">DATE: {{ result.time }}</h1>
                    <h1 class="flex gap-2 text-white font-bold">TEMPERATURE: {{ result.values.temperatureApparentAvg }} <span v-if="result.values.temperatureApparentAvg
                    < 26 && result.values.temperatureApparentAvg > 10"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M558-84q-15 8-30.5 2.5T504-102l-60-120q-8-15-2.5-30.5T462-276q15-8 30.5-2.5T516-258l60 120q8 15 2.5 30.5T558-84Zm240 0q-15 8-30.5 2.5T744-102l-60-120q-8-15-2.5-30.5T702-276q15-8 30.5-2.5T756-258l60 120q8 15 2.5 30.5T798-84Zm-480 0q-15 8-30.5 2.5T264-102l-60-120q-8-15-2.5-30.5T222-276q15-8 30.5-2.5T276-258l60 120q8 15 2.5 30.5T318-84Zm-18-236q-91 0-155.5-64.5T80-540q0-83 55-145t136-73q32-57 87.5-89.5T480-880q90 0 156.5 57.5T717-679q69 6 116 57t47 122q0 75-52.5 127.5T700-320H300Zm0-80h400q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-40q0-66-47-113t-113-47q-48 0-87.5 26T333-704l-10 24h-25q-57 2-97.5 42.5T160-540q0 58 41 99t99 41Zm180-200Z"/></svg></span>
                        <span v-if="result.values.temperatureApparentAvg
                    > 26"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M440-760v-160h80v160h-80Zm266 110-55-55 112-115 56 57-113 113Zm54 210v-80h160v80H760ZM440-40v-160h80v160h-80ZM254-652 140-763l57-56 113 113-56 54Zm508 512L651-255l54-54 114 110-57 59ZM40-440v-80h160v80H40Zm157 300-56-57 112-112 29 27 29 28-114 114Zm283-100q-100 0-170-70t-70-170q0-100 70-170t170-70q100 0 170 70t70 170q0 100-70 170t-170 70Zm0-80q66 0 113-47t47-113q0-66-47-113t-113-47q-66 0-113 47t-47 113q0 66 47 113t113 47Zm0-160Z"/></svg></span>

                    <span v-if="result.values.temperatureApparentAvg <= 10"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M440-80v-166L310-118l-56-56 186-186v-80h-80L174-254l-56-56 128-130H80v-80h166L118-650l56-56 186 186h80v-80L254-786l56-56 130 128v-166h80v166l130-128 56 56-186 186v80h80l186-186 56 56-128 130h166v80H714l128 130-56 56-186-186h-80v80l186 186-56 56-130-128v166h-80Z"/></svg></span>
    </h1>
                    <h1 class="text-white font-bold">VISIBILITY:  {{ result.values.visibilityAvg }}</h1>
                    <h1 class="text-white font-bold">HUMIDITY:  {{ result.values.humidityAvg}}</h1>
                    <h1 class="text-white font-bold">WIND SPEED:  {{ result.values.windSpeedAvg }}</h1>
                </div>
            </div>
        </div>
        
        <div class="w-11/12 ml-5 mt-5 mr-5 mb-20 relative flex gap-5  flex-wrap p-5" v-if="todo">
            <div v-for="(todo, index) in props.todos" class="w-3/12 h-64 relative flex  flex-col rounded-lg overflow-y-scroll cursor-pointer no-scrollbar" :key="index" >
                <div class="w-full relative p-2 bg-blue-800 rounded-lg" >
                    <div class="flex justify-between mb-5 items-center text-white">
                        <span class="text-xs">{{ todo.date }}</span>
                        <span @click="deleteTodo(todo.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#e8eaed"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                        </span>
                    </div>
                    <div class="hover:bg-blue-600 overflow-y-scroll no-scrollbar w-full no-scrollbar rounded " @click="show(todo.id, todo.title, todo.subject)" >
                        <h1 class="font-bold text-white text-sm "  v-if="todo.title != null">{{ todo.title }}</h1>
                        <span class=" text-white text-sm" style="word-wrap: break-word;">{{ todo.subject }}</span>
                    </div>
                    
                </div>
         
            </div>
        </div>


</div>




</template>