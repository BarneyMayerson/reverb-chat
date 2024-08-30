<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import ChatMessage from "@/Components/ChatMessage.vue";
import MessageForm from "@/Components/Forms/Chat/MessageForm.vue";

const props = defineProps(["chat"]);

const page = usePage();

const me = page.props.auth.user;
</script>

<template>
  <Head title="Chat" />
  <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="px-4 py-8">
      <h3 class="text-xl">
        Your private chat with
        <Link href="/" class="font-bold hover:underline">
          {{ chat.partner.name }}
        </Link>
        .
      </h3>
      <div
        class="flex flex-col-reverse h-96 border border-gray-300 dark:border-gray-700 rounded-lg px-4 overflow-y-auto mt-4"
      >
        <template v-for="message in chat.messages" :key="message.id">
          <div class="w-full space-y-2">
            <ChatMessage
              :align-right="message.sender_id === me.id"
              :message
              :imageSrc="
                message.sender_id === me.id
                  ? me.profile_photo_url
                  : chat.partner.profile_photo_url
              "
            />
          </div>
        </template>
      </div>
      <MessageForm :chat class="mt-6" />
    </div>
  </div>
</template>
