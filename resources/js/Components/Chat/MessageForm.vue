<script setup>
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps(["channel", "chat", "me", "isPartnerTyping"]);

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
    },
  );
};

const sendTypingEvent = () => {
  props.channel.whisper("typing", {
    userId: props.me.id,
  });
};
</script>

<template>
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

    <div class="ml-3 mt-1.5">
      <p
        class="text-xs font-bold tracking-wide transition-opacity"
        :class="isPartnerTyping ? 'opacity-100' : 'opacity-0'"
      >
        {{ chat.partner.name }} is typing ...
      </p>
    </div>

    <div class="mt-1 flex justify-end">
      <PrimaryButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Send
      </PrimaryButton>
    </div>
  </form>
</template>
