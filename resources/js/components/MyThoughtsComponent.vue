<template>
    <div class="row">
<!--        <form-component @new="addThought"></form-component>-->
        <div class="col s4"
             v-for="(thought, index) in thoughts"
             :key="thought.id">
            <thought-component
                :thought="thought"
                @update="updateThought(index, ...arguments)"
                @delete="deleteThought(index)">
            </thought-component>
        </div>
        <modal-nuevo-producto @new="addThought"></modal-nuevo-producto>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                thoughts: []
            }
        },

        mounted() {
            axios.get('/thoughts').then((response) => {
                this.thoughts = response.data;
            });
        },

        methods: {
            addThought(thought) {
                this.thoughts.push(thought);
            },
            updateThought(index, thought) {
                this.thoughts[index] = thought;
            },
            deleteThought(index) {
                this.thoughts.splice(index, 1);
            }
        }
    }
</script>
