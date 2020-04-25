<template>
    <div class="row">
        <div class="col s12 m3 l4 xl2"
             v-for="(thought, index) in thoughts"
             :key="thought.id">
            <thought-component
                :thought="thought"
                @update="updateThought(index, ...arguments)"
                @delete="deleteThought(index)">
            </thought-component>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                thoughts: [],
                sel: 'inicio'
            }
        },

        mounted() {
            axios.get('/producto').then((response) => {
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
