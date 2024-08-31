<script setup>
import { nextTick, onMounted, ref, watch } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import ChatMessage from "@/Components/ChatMessage.vue";
import MessageForm from "@/Components/Forms/Chat/MessageForm.vue";

const props = defineProps(["chat"]);

const messages = ref();
messages.value = props.chat.messages;

const page = usePage();

const me = page.props.auth.user;

onMounted(() => {
  Echo.private(`Chat.${props.chat.id}`).listen("Chat\\MessageSent", (e) => {
    const message = e.message;
    messages.value.push(message);
  });

  scrollMessagesContainer();
});

const messagesContainer = ref(null);

const scrollMessagesContainer = () => {
  messagesContainer.value.scrollTo({
    top: messagesContainer.value.scrollHeight,
    behavior: "smooth",
  });
};

watch(
  messages,
  () => {
    nextTick(() => {
      scrollMessagesContainer();
    });
  },
  { deep: true }
);
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
      <div class="flex flex-col justify-end h-80">
        <div ref="messagesContainer" class="p-4 overflow-y-auto max-h-fit">
          <div
            v-for="message in messages"
            :key="message.id"
            class="flex items-center mb-2"
          >
            <div
              v-if="message.sender_id === me.id"
              class="p-2 ml-auto text-white bg-blue-500 rounded-lg"
            >
              {{ message.text }}
            </div>
            <div v-else class="p-2 mr-auto bg-gray-200 rounded-lg">
              {{ message.text }}
            </div>
          </div>
        </div>
      </div>
      <div
        class="mt-16 flex flex-col overflow-y-auto h-96 border border-gray-300 dark:border-gray-700 rounded-lg px-4"
      >
        <div v-for="message in messages" :key="message.id">
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
      </div>
      <MessageForm :chat class="mt-6" />
    </div>
  </div>
</template>
