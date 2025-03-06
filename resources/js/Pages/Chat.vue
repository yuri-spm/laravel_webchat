<script setup>
import { ref, nextTick, onMounted } from 'vue';
import axios from 'axios';
import moment from 'moment';
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

axios.defaults.withCredentials = true;

const users = ref([]);
const messages = ref([]);
const page = usePage();
const userActive = ref(null);
const message =  ref('');


const formatDate = (value) => {
    return value ? moment(value).format("HH:mm") : "";
};

const loadmessages = async (userId) => {
    axios.get(`api/users/${userId}`).then(response => {
        userActive.value = response.data.data;
    });

    axios.get(`api/messages/${userId}`).then(response => {
        messages.value = response.data.messages;

        nextTick(() => {
            scrollToBottom();
        });
    });
};


const scrollToBottom = () => {
    nextTick(() => {
        const chatContainer = document.querySelector('.chat-container');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
};

const sendMessage = async() => {
    axios.post('api/messages/store', {
        to: userActive.value.id,
        content: message.value
    }).then(response => {
        messages.value.push({
            from: page.props.auth.user.id,
            to: userActive.value.id,
            content: message.value,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
        });
        message.value = '';
        scrollToBottom();
    }).catch(error => {
        console.error("Erro ao enviar mensagem:", error);
    });
    
};


onMounted(() => {
    axios.get('api/users')
        .then(response => {
            users.value = response.data.data;
        })
        .catch(error => {
            console.error('Erro ao buscar usuários:', error);
        });
});
</script>


<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat
            </h2>
        </template>

        <div class="py-6">
            <div class="w-full px-4">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex h-[calc(100vh-180px)]">
                    <!--List Users-->
                    <div class="w-3/12 bg-gray-200 bg-opacity-25 border-r border-gray-200 overflow-y-scroll">
                        <ul>
                            <li v-for="user in users" :key="user.id"
                                @click="() => loadmessages(user.id)"
                                class="p-6 text-lg text-gray-600 leading-7 font-semibold border-b border-gray-200 hover:bg-gray-200 hover:bg-opacity-50 hover:cursor-pointer">
                                <p class="flex items-center">
                                    {{ user.name }}
                                    <span class="ml-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                                </p>
                            </li>
                        </ul>
                    </div>
                    
                    <!--box Message-->
                    <div class="w-9/12 flex flex-col justify-between">
                        <!--box Message-->
                        <div class="w-full p-6 flex flex-col overflow-y-scroll chat-container">
                            <div 
                                v-for="message in messages" :key="message.id"
                                :class="(message.from == $page.props.auth.user.id) ? ' text-right' : ''"
                                class="w-full mb-3 message">
                                <p 
                                    :class="(message.from == $page.props.auth.user.id) ? 'messageFromMe' : ''"
                                    class="inline-block p-2 rounded-md" style="max-width: 75%;">
                                    {{ message.content }}
                                </p>
                                <span class="block mt-1 text-xs text-gray-500">{{ formatDate(message.created_at) }}</span>
                            </div>
                            <div class="w-full mb-3 text-left">
                                <p class="inline-block p-2 rounded-md messageTomMe" style="max-width: 75%;"> Mensagem static
                                </p>
                                <span class="block mt-1 text-xs text-gray-500">19:25</span>
                            </div>
                        </div>

                        <!--form-->
                        <div v-if="userActive" class="w-full bg-gray-200 bg-opacity-25 p-6 border-t border-gray-200">
                            <form v-on:submit.prevent="sendMessage">
                                <div class="flex rounded-md overflow-hidden border border-gray-300">
                                    <input v-model='message' type="text" class="flex-1 px-4 py-2 text-sm focus-within:none">
                                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.messageFromMe {
    background-color: rgba(99, 102, 241, 0.25); 
}

.messageTomMe {
    background-color: rgba(209, 213, 219, 0.5); 
}
</style>