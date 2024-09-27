<script setup>
import { Modal } from "momentum-modal";
import Banner from "@/Components/Banner.vue";
import PrimaryMenu from "@/Components/Menus/PrimaryMenu.vue";
</script>

<template>
  <div>
    <Banner />

    <Modal />

    <PrimaryMenu
      :has-auth-user="!!$page.props.auth.user"
      :current-route="route().current()"
    />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
      <!-- Page Heading -->
      <header v-if="$slots.header" class="bg-white shadow dark:bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>
      <!-- Page Content -->
      <main class="text-gray-950 dark:text-gray-50">
        <slot />
      </main>
    </div>
  </div>
</template>
