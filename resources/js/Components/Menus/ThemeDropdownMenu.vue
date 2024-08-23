<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import SunSolid from "@/Icons/SunSolid.vue";
import MoonSolid from "@/Icons/MoonSolid.vue";
import DesktopComputerSolid from "@/Icons/DesktopComputerSolid.vue";

defineProps({
  align: {
    type: String,
    default: "right",
  },
  width: {
    type: String,
    default: "32",
  },
});

const theme = ref("system");
const themes = ["light", "dark"];

const matcher = window.matchMedia("(prefers-color-scheme: dark)");
const listener = () => {
  if (theme.value === "system") {
    applySystemTheme();
  }
};

const applySystemTheme = () => {
  if (matcher.matches) {
    document.documentElement.classList.add("dark");
  } else {
    document.documentElement.classList.remove("dark");
  }
};

onMounted(() => {
  if (themes.includes(localStorage.appTheme)) {
    theme.value = localStorage.appTheme;
  }

  matcher.addEventListener("change", listener);

  listener();
});

onBeforeUnmount(() => {
  matcher.removeEventListener("change", listener);
});

watch(theme, (themeValue) => {
  if (themeValue === "light") {
    localStorage.appTheme = "light";
    document.documentElement.classList.remove("dark");
  }
  if (themeValue === "dark") {
    localStorage.appTheme = "dark";
    document.documentElement.classList.add("dark");
  }
  if (themeValue === "system") {
    localStorage.removeItem("appTheme");
    applySystemTheme();
  }
});

const setLightTheme = () => {
  theme.value = "light";
};
const setDarkTheme = () => {
  theme.value = "dark";
};
const setSystemTheme = () => {
  theme.value = "system";
};
</script>

<template>
  <Dropdown :align :width>
    <template #trigger>
      <button
        class="text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out p-2"
      >
        <SunSolid v-if="theme === 'light'" />
        <MoonSolid v-if="theme === 'dark'" />
        <DesktopComputerSolid v-if="theme === 'system'" />
      </button>
    </template>

    <template #content>
      <DropdownLink @click.prevent="setLightTheme" as="button">
        <div class="flex items-center">
          <SunSolid />
          <span class="ml-4">Light</span>
        </div>
      </DropdownLink>
      <DropdownLink @click.prevent="setDarkTheme" as="button">
        <div class="flex items-center">
          <MoonSolid />
          <span class="ml-4">Dark</span>
        </div>
      </DropdownLink>
      <DropdownLink @click.prevent="setSystemTheme" as="button">
        <div class="flex items-center">
          <DesktopComputerSolid />
          <span class="ml-4">System</span>
        </div>
      </DropdownLink>
    </template>
  </Dropdown>
</template>
