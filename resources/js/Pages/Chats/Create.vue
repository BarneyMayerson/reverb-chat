<script setup>
import { useModal } from "momentum-modal";
import { useForm } from "@inertiajs/vue3";
import SlideDialogModal from "@/Components/SlideDialogModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps(["user"]);

const { close } = useModal();

const form = useForm("MessageForm", {
  message: `Hello, ${props.user.name}.\nHow are you?`,
});

const submit = () => {
  form.post(
    route("chats.store", {
      message: form.message,
      recipient_id: props.user.id,
    }),
    {
      preserveScroll: true,
      onSuccess: () => {
        form.message = "";
        close;
      },
    }
  );
};
</script>

<template>
  <SlideDialogModal
    @close="close"
    max-width="xl"
    dialog-title="Let's start a new chat"
  >
    <template #content>
      <p class="text-center text-sm">New message</p>
      <div class="mt-4 flex space-x-4">
        <img
          :src="user.profile_photo_url"
          :alt="user.name"
          class="rounded-full h-20 w-20 object-cover"
        />
        <p>{{ user.name }}</p>
      </div>
    </template>

    <template #footer>
      <form @submit.prevent="submit" class="w-full">
        <TextArea id="message" v-model="form.message" />
        <InputError
          :message="form.errors.message"
          class="mt-2 ml-1 text-left"
        />
        <div class="mt-4">
          <SecondaryButton @click="close">Cancel</SecondaryButton>
          <SecondaryButton type="submit" class="ml-4">Save</SecondaryButton>
        </div>
      </form>
    </template>
  </SlideDialogModal>
</template>
