<script setup>
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps(["chats"]);
</script>

<template>
  <Head title="Chats" />
  <section class="container mx-auto">
    <div class="space-y-4 p-6 sm:p-8 md:space-y-6">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold leading-tight tracking-tight md:text-2xl">
          Your Chats
        </h2>
      </div>
      <div class="grid gap-4 md:grid-cols-2 md:gap-8 xl:grid-cols-3">
        <Link
          v-for="chat in chats"
          :key="chat.id"
          :href="route('chats.show', chat.id)"
          class="block rounded-lg border p-4 shadow"
        >
          <div class="flex items-center space-x-2">
            <img
              :src="chat.partner.profile_photo_url"
              class="size-16 rounded-full object-cover"
              alt="avatar"
            />
            <span class="text-lg">{{ chat.partner.name }}</span>
          </div>
        </Link>
      </div>
    </div>
  </section>
</template>
