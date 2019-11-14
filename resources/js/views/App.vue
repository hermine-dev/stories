<template>
    <div>
        <h1>Story App</h1>
        <p>
            <router-link :to="{ name: 'stories.add' }">Add Story</router-link> |
            <router-link :to="{ name: 'stories' }">Stories</router-link>
        </p>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    export default {}
</script>
