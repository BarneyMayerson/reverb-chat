<script setup>
import Welcome from "@/Components/Welcome.vue";
import PageHeader from "@/Components/PageHeader.vue";
</script>

<template>
  <PageHeader title="Dashboard" />

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div
        class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
      >
        <Welcome />
      </div>
    </div>
  </div>
</template>
