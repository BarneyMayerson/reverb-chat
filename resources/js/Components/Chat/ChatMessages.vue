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
  { deep: true }
);
</script>

<template>
  <div
    class="flex flex-col justify-end h-96 py-2 mt-8 border border-gray-300 dark:border-gray-800 rounded-lg"
  >
    <div ref="messagesContainer" class="p-4 overflow-y-auto max-h-fit">
      <div
        v-for="message in messages"
        :key="message.id"
        class="flex items-center mb-4"
      >
        <div
          v-if="message.sender_id === me.id"
          class="py-2 px-4 w-5/6 ml-auto text-white bg-blue-500 dark:bg-blue-700 rounded-lg"
        >
          <MyMessage :message />
        </div>
        <div
          v-else
          class="py-2 px-4 w-5/6 mr-auto bg-gray-200 dark:bg-gray-700 dark:text-gray-50 rounded-lg"
        >
          <PartnerMessage :message :imageSrc="chat.partner.profile_photo_url" />
        </div>
      </div>
    </div>
  </div>

  <MessageForm :channel :chat :me :isPartnerTyping />
</template>
