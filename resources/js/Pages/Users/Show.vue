<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { distanceToNow } from "@/utilities/date";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps(["user", "you", "chat_id"]);

const formattedDate = computed(() => distanceToNow(props.user.created_at));

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
        }),
      )
      .leaving(
        (user) =>
          (onlineUsers.value = onlineUsers.value.filter(
            ({ id }) => id !== user.id,
          )),
      );
  }
});

onUnmounted(() => {
  Echo.leave("OnlineUsers");
});

const isOnline = () => onlineUsers.value.find(({ id }) => id === props.user.id);
</script>

<template>
  <Head title="User Profile" />

  <div class="container mx-auto">
    <div class="p-6 sm:p-8">
      <h2 class="text-xl font-bold leading-tight tracking-tight md:text-2xl">
        User Profile
      </h2>

      <div class="mt-8 flex space-x-8">
        <div class="relative">
          <img
            :src="user.profile_photo_url"
            :alt="user.name"
            class="h-20 w-20 rounded-full object-cover"
          />
          <span
            v-if="you"
            class="absolute bottom-0 right-0 size-3 rounded-full"
            :class="isOnline() ? 'bg-green-500' : 'bg-red-500'"
          ></span>
        </div>
        <div>
          <p class="text-xl">{{ user.name }}</p>
          <p class="mt-1 text-xs">Since {{ formattedDate }}</p>
        </div>
      </div>

      <div class="mt-4">
        <div v-if="chat_id">
          <Link :href="route('chats.show', chat_id)">
            <PrimaryButton>Go to chat</PrimaryButton>
          </Link>
        </div>
        <div v-else>
          <Link :href="route('chats.create', user.id)">
            <PrimaryButton>Start chat</PrimaryButton>
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>
