<script setup>
import { computed } from "vue";
import { Head } from "@inertiajs/vue3";
import { distanceToNow } from "@/utilities/date";

const props = defineProps(["user"]);

const formattedDate = computed(() => distanceToNow(props.user.created_at));
</script>

<template>
  <Head title="User Profile" />

  <div class="container mx-auto">
    <div class="p-6 sm:p-8">
      <h2 class="text-xl font-bold leading-tight tracking-tight md:text-2xl">
        User Profile
      </h2>

      <div class="flex space-x-8 mt-8">
        <img
          :src="user.profile_photo_url"
          :alt="user.name"
          class="rounded-full h-20 w-20 object-cover"
        />
        <div>
          <p class="text-xl">{{ user.name }}</p>
          <p class="text-xs mt-1">Since {{ formattedDate }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
