<template>
    <div class="page-wrapper">
        <div class="story-header">
            <h1>Story App</h1>
            <ul>
              <li><router-link :to="{ name: 'stories.add' }">Add Story</router-link></li>
              <li><router-link :to="{ name: 'stories' }">Stories</router-link></li>
            </ul>
        </div>
        <div class="">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    export default {}
</script>
