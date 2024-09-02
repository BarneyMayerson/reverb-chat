<script setup>
import { nextTick, onMounted, ref, watch } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import MyMessage from "@/Components/Chat/MyMessage.vue";
import PartnerMessage from "@/Components/Chat/PartnerMessage.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps(["chat"]);

const messages = ref(props.chat.messages);

const page = usePage();

const me = page.props.auth.user;

const form = useForm("MessageForm", {
  text: "",
});

const submit = () => {
  form.post(
    route("chats.message", {
      chat: props.chat,
      text: form.text,
    }),
    {
      preserveScroll: true,
      onSuccess: () => {
        form.text = "";
      },
    }
  );
};

const isPartnerTyping = ref(false);

const channel = Echo.private(`Chat.${props.chat.id}`)
  .listen("Chat\\MessageSent", (e) => {
    const message = e.message;
    messages.value.push(message);
  })
  .listenForWhisper("typing", (response) => {
    isPartnerTyping.value = response.userId === props.chat.partner.id;
  });

const sendTypingEvent = () => {
  channel.whisper("typing", {
    userId: me.id,
  });
};

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
              <PartnerMessage
                :message
                :imageSrc="chat.partner.profile_photo_url"
              />
            </div>
          </div>
        </div>
      </div>

      <form class="mt-6" @submit.prevent="submit">
        <InputLabel for="message" value="New message" class="ml-3" />
        <TextArea
          @keydown="sendTypingEvent()"
          id="message"
          rows="3"
          v-model="form.text"
          class="mt-1 block w-full"
        />
        <InputError :message="form.errors.text" class="mt-2" />

        <div class="mt-1.5 ml-3">
          <p v-if="isPartnerTyping" class="text-xs font-bold tracking-wide">
            {{ chat.partner.name }} is typing ...
          </p>
        </div>

        <div class="mt-6 flex justify-end">
          <PrimaryButton
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Send
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
