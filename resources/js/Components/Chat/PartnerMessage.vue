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
      class="size-5 object-cover rounded-xl flex-shrink-0"
      alt="profile_photo"
    />
    <span>{{ message.text }}</span>
  </div>
  <span
    class="block text-xs font-semibold text-gray-500 dark:text-gray-400 text-right"
  >
    {{ message.created_at }}
  </span>
</template>
