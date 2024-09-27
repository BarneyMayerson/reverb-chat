<script setup>
defineProps({
  message: {
    type: Object,
    required: true,
  },
  imageSrc: {
    type: String,
    required: true,
  },
});
</script>

<template>
  <div class="flex items-center space-x-4">
    <img
      :src="imageSrc"
      class="size-5 flex-shrink-0 rounded-xl object-cover"
      alt="profile_photo"
    />
    <span>{{ message.text }}</span>
  </div>
  <span
    class="block text-right text-xs font-semibold text-gray-500 dark:text-gray-400"
  >
    {{ message.created_at }}
  </span>
</template>
