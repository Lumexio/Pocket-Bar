<template>
  <div class="bg-blue-gray-50" x-data="initApp()" x-init="initDatabase()">
    <!-- noprint-area -->
    <div
      class="hide-print flex flex-row h-screen antialiased text-blue-gray-800"
    >
      @include("pos/left-sidebar")

      <!-- page content -->
      <div class="flex-grow flex w-5/6">@include('pos/menu')</div>

      @include("pos.right-sidebar") @include("pos/confirm-modal")
    </div>
    <!-- end of noprint-area -->

    <div id="print-area" class="print-area"></div>
  </div>
</template>
<script>
export default {
  name: "mainPos", // you can enter any name (optional)
  components: {},
};
</script>

<style scoped>
/* your style */
.background {
  z-index: 0;
}
.background:before {
  content: "";
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  height: 0%;
  z-index: -1;
  display: flex;
  flex-direction: column;
  background-color: #ffffff;
  background-position: top left;
  background-repeat: repeat;
  background-size: auto;
  background-attachment: auto;
  border-bottom: 1px solid #eaeaeb;
}
</style>