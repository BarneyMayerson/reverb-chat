<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps(["users", "you"]);

const showPagination = computed(() => props.users.meta.last_page > 1);
</script>

<template>
  <Head title="Users" />
  <div class="container mx-auto">
    <div class="p-6 sm:p-8">
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold leading-tight tracking-tight md:text-2xl">
          Users List
        </h2>
      </div>

      <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="user in users.data"
          :key="user.id"
          class="overflow-hidden shadow-sm sm:rounded-lg bg-gray-200 dark:bg-gray-700 dark:text-gray-50 hover:scale-105 duration-200"
        >
          <Link :href="route('users.index')">
            <div class="p-4">
              <div class="flex items-center">
                <span>{{ user.name }}</span>
                <span
                  v-if="you?.id === user.id"
                  class="ml-2 text-xs rounded-lg px-2 py-0.5 bg-blue-300 dark:bg-blue-600"
                >
                  it's you
                </span>
              </div>
            </div>
          </Link>
        </div>
      </div>

      <Pagination v-if="showPagination" :meta="users.meta" />
    </div>
  </div>
</template>
