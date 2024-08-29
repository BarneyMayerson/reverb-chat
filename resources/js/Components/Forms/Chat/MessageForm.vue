<script setup>
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps(["chat"]);

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
</script>

<template>
  <form @submit.prevent="submit">
    <InputLabel for="message" value="New message" />
    <TextArea
      id="message"
      rows="3"
      v-model="form.text"
      class="mt-1 block w-full"
    />
    <InputError :message="form.errors.text" class="mt-2" />

    <div class="mt-6 flex justify-end">
      <PrimaryButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Send
      </PrimaryButton>
    </div>
  </form>
</template>
