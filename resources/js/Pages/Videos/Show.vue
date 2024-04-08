<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    video: Object,
    upload: Object
})

const form = useForm({
    title: props.video.title,
    description: props.video.description
})

const deleteVideo = () => {
    if (window.confirm('Are you sure?')) {
        router.delete(route('videos.destroy', props.video))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <InputLabel for="share_url" value="Share link" />
                        <input type="text" v-bind:value="video.share_url" readonly class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" v-on:click="$event.target.select()">
                    </div>
                </div>


                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="max-w-[240px] w-full space-y-3">

                            <div class="space-y-1">
                                <div class="bg-gray-100 shadow-inner h-3 rounded overflow-hidden">
                                    <div class="bg-green-500 h-full" v-bind:style="{ width: `%` }"></div>
                                </div>
                                <div class="text-sm">
                                    Encoding
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="bg-gray-100 shadow-inner h-3 rounded overflow-hidden">
                                    <div class="bg-blue-500 h-full" v-bind:style="{ width: `%` }"></div>
                                </div>
                                <div class="text-sm">
                                    Uploading
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <button class="text-blue-500 text-sm font-medium" v-on:click="emit('pause', 1)">
                                    Pause
                                </button>
                                <button class="text-blue-500 text-sm font-medium" v-on:click="emit('resume', 1)">
                                    Resume
                                </button>

                                <button class="text-blue-500 text-sm font-medium" v-on:click="emit('cancel', 1)">
                                    Cancel upload
                                </button>
                            </div>

                        </div>
                        <form class="space-y-6" v-on:submit.prevent="form.patch(route('videos.update', video), { preserveScroll: true })">
                            <video controls :src="`/storage/${video.video_path}`"></video>

                            <div>
                                <InputLabel for="title" value="Title" />
                                <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel for="description" value="Description" />
                                <Textarea id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                            <div class="flex items-center justify-between ">
                                <div class="flex items-center space-x-2">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Save video
                                    </PrimaryButton>
                                    <div class="text-sm text-gray-600" v-if="form.recentlySuccessful">
                                        Video saved
                                    </div>
                                </div>

                                <div>
                                    <DangerButton v-on:click="deleteVideo" type="button">
                                        Delete Video
                                    </DangerButton>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
