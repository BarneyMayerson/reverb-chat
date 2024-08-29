<script setup>
defineProps({
  alignRight: {
    type: Boolean,
    required: true,
  },
  message: {
    type: Object,
    required: true,
  },
  imageSrc: {
    type: String,
  },
});
</script>

<template>
  <div
    class="mb-3 w-5/6 bg-gray-100 dark:bg-gray-950 border border-gray-300 dark:border-gray-800 rounded-lg px-4 py-2"
    :class="{ 'float-right': alignRight }"
  >
    <div class="flex items-center space-x-4">
      <img
        :src="imageSrc"
        class="size-5 object-cover rounded-xl"
        alt="profile_photo"
      />
      <span>{{ message.text }}</span>
    </div>
    <span
      class="block text-xs font-semibold text-gray-500 dark:text-gray-400 text-right"
    >
      {{ message.created_at }}
    </span>
  </div>
</template>
