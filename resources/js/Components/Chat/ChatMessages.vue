<script setup>
import { nextTick, onMounted, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import MyMessage from "@/Components/Chat/MyMessage.vue";
import PartnerMessage from "@/Components/Chat/PartnerMessage.vue";
import MessageForm from "@/Components/Chat/MessageForm.vue";

const props = defineProps(["chat"]);

const messages = ref(props.chat.messages);

const page = usePage();

const me = page.props.auth.user;

const isPartnerTyping = ref(false);
const partnerTypingTimer = ref(null);

const channel = Echo.private(`Chat.${props.chat.id}`)
  .listen("Chat\\MessageSent", (e) => {
    const message = e.message;
    messages.value.push(message);
  })
  .listenForWhisper("typing", (response) => {
    isPartnerTyping.value = response.userId === props.chat.partner.id;

    if (partnerTypingTimer.value) {
      clearTimeout(partnerTypingTimer.value);
    }

    partnerTypingTimer.value = setTimeout(() => {
      isPartnerTyping.value = false;
    }, 1500);
  });

onMounted(() => {
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
  { deep: true },
);
</script>

<template>
  <div
    class="mt-8 flex h-96 flex-col justify-end rounded-lg border border-gray-300 py-2 dark:border-gray-800"
  >
    <div ref="messagesContainer" class="max-h-fit overflow-y-auto p-4">
      <div
        v-for="message in messages"
        :key="message.id"
        class="mb-4 flex items-center"
      >
        <div
          v-if="message.sender_id === me.id"
          class="ml-auto w-5/6 rounded-lg bg-blue-500 px-4 py-2 text-white dark:bg-blue-700"
        >
          <MyMessage :message />
        </div>
        <div
          v-else
          class="mr-auto w-5/6 rounded-lg bg-gray-200 px-4 py-2 dark:bg-gray-700 dark:text-gray-50"
        >
          <PartnerMessage :message :imageSrc="chat.partner.profile_photo_url" />
        </div>
      </div>
    </div>
  </div>

  <MessageForm :channel :chat :me :isPartnerTyping />
</template>
