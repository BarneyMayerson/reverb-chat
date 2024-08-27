<script setup>
import { Head, router } from "@inertiajs/vue3";

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  laravelVersion: {
    type: String,
    required: true,
  },
  phpVersion: {
    type: String,
    required: true,
  },
});

function handleImageError() {
  document.getElementById("screenshot-container")?.classList.add("!hidden");
  document.getElementById("docs-card")?.classList.add("!row-span-1");
  document.getElementById("docs-card-content")?.classList.add("!flex-row");
  document.getElementById("background")?.classList.add("!hidden");
}

Echo.channel("reactions").listen("ReactionEvent", (e) => {
  const button = document.querySelector(`#${e.buttonId}`);

  flyEmoji(e.emoji, button);
});

const clickEmoji = (e) => {
  router.post(route("reactions.store"), {
    buttonId: e.target.id,
    emoji: e.target.innerText,
  });
};

function flyEmoji(emoji, button) {
  const rect = button.getBoundingClientRect();
  const flyEmoji = document.createElement("div");
  flyEmoji.innerText = emoji;
  flyEmoji.style.position = "fixed";
  flyEmoji.style.left = `${rect.left + rect.width / 2}px`;
  flyEmoji.style.top = `${rect.top + rect.height / 2}px`;
  flyEmoji.style.transition = "all 1s ease-out";
  flyEmoji.style.fontSize = "3rem";
  flyEmoji.style.pointerEvents = "none";

  document.body.appendChild(flyEmoji);

  setTimeout(() => {
    flyEmoji.style.top = `${rect.top - rect.height}px`;
    flyEmoji.style.opacity = 0;
    flyEmoji.style.transform = "translate(-50%, -50%) scale(2)";
  }, 10);

  setTimeout(() => {
    flyEmoji.remove();
  }, 1000);
}
</script>

<template>
  <Head title="Welcome" />
  <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
    <div
      class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
    >
      <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
        <main class="mt-6">
          <div class="flex justify-center items-center my-12">
            <button @click="clickEmoji" id="heart" class="reaction-button">
              ❤️
            </button>
            <button @click="clickEmoji" id="star" class="reaction-button">
              ⭐
            </button>
            <button @click="clickEmoji" id="rocket" class="reaction-button">
              🚀
            </button>
          </div>
        </main>

        <footer class="py-16 text-center text-sm text-black dark:text-white/70">
          Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
        </footer>
      </div>
    </div>
  </div>
</template>

<style scoped>
.reaction-button {
  @apply flex items-center justify-center m-4 p-4 text-3xl w-16 h-16 bg-transparent border border-white border-opacity-50 text-white rounded-full hover:bg-white hover:bg-opacity-50 focus:outline-none transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110;
}
</style>
