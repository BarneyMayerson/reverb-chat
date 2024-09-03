<script setup>
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps(["chats"]);
</script>

<template>
  <Head title="Chats" />
  <section class="container mx-auto">
    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold leading-tight tracking-tight md:text-2xl">
          Your Chats
        </h2>
      </div>
      <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3 md:gap-8">
        <Link
          v-for="chat in chats"
          :key="chat.id"
          :href="route('chats.show', chat.id)"
          class="block p-4 rounded-lg border shadow"
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
