<template>
    <div class="template-empty-page">
        <div class="empty-animation">
            <img :src="image" alt="">
        </div>
        <div class="empty-wording">
            <p>{{message}}</p>
        </div>
    </div>
</template>

<script>

    export default {
        props : ['message', 'image'],
        name : 'BlankPage'
    }
</script>
