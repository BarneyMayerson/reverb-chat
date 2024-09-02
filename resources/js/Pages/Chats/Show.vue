<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ChatMessages from "@/Components/Chat/ChatMessages.vue";

const props = defineProps(["chat"]);
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
      <ChatMessages :chat />
    </div>
  </div>
</template>
