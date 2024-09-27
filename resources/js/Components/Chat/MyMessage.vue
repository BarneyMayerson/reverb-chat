<script setup>
defineProps({
  message: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  {{ message.text }}
  <span class="block text-right text-xs font-semibold text-gray-100">
    {{ message.created_at }}
  </span>
</template>
