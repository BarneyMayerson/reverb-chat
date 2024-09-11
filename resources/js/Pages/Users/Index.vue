<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps(["users", "you", "query"]);

const showPagination = computed(() => props.users.meta.last_page > 1);

const onlineUsers = ref([]);

onMounted(() => {
  if (props.you) {
    Echo.join("OnlineUsers")
      .here((users) => (onlineUsers.value = users))
      .joining((user) =>
        router.reload({
          onSuccess: () => {
            onlineUsers.value.push(user);
          },
        })
      )
      .leaving(
        (user) =>
          (onlineUsers.value = onlineUsers.value.filter(
            ({ id }) => id !== user.id
          ))
      );
  }
});

onUnmounted(() => {
  Echo.leave("OnlineUsers");
});

const searchForm = useForm({
  query: props.query,
  page: 1,
});

const page = usePage();

const search = () => searchForm.get(page.url);

const clearSearch = () => {
  searchForm.query = "";
  search();
};
</script>

<template>
  <Head title="Users" />
  <div class="container mx-auto">
    <div class="p-6 sm:p-8">
      <div class="flex justify-between items-center space-x-6">
        <h2 class="text-xl font-bold leading-tight tracking-tight md:text-2xl">
          Users List
        </h2>

        <form @submit.prevent="search" class="flex-1">
          <div class="mt-1 flex space-x-2">
            <InputLabel for="query" class="sr-only">Search</InputLabel>
            <TextInput
              v-model="searchForm.query"
              id="query"
              class="w-full"
              placeholder="A name looks like ..."
            />
            <SecondaryButton type="submit">Search</SecondaryButton>
            <DangerButton v-if="searchForm.query" @click="clearSearch">
              Clear
            </DangerButton>
          </div>
        </form>
      </div>

      <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="user in users.data"
          :key="user.id"
          class="overflow-hidden shadow-sm sm:rounded-lg bg-gray-200 dark:bg-gray-700 dark:text-gray-50 hover:scale-105 duration-200"
        >
          <Link :href="route('users.show', user.id)">
            <div class="p-4">
              <div class="flex items-center space-x-2">
                <span>{{ user.name }}</span>
                <span
                  v-if="you"
                  class="size-2 rounded-full"
                  :class="
                    onlineUsers.find(({ id }) => id === user.id)
                      ? 'bg-green-500'
                      : 'bg-red-500'
                  "
                ></span>
                <span
                  v-if="you?.id === user.id"
                  class="text-xs rounded-lg px-2 py-0.5 bg-blue-300 dark:bg-blue-600"
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
