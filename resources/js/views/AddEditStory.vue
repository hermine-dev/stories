<template>
    <div class="container">
        <form @submit.prevent="onSubmit()">
            <div class="form-group c-form">
                <label for="page_name">Page Name:</label>
                <input id="page_name" v-model="story.page_name"/>
            </div>
            <div class="form-group text-center">
                <button v-on:click="addFiles()" type="button" class="save-btn">Add Stories</button>
            </div>
            <div class="form-group">
                <label>
                    <input type="file" id="files" ref="files" multiple v-on:change="handleFilesUpload()"/>
                </label>
            </div>
            <div class="form-group d-flex justify-content-start flex-wrap">
                <div v-for="(item, keyItem) in story.items" class="file-listing">
                  <span class="remove-file" v-on:click="removeFileItem( keyItem )">
                    <img src="/img/garbage.png" alt="">
                  </span>
                    <img v-if="item.type === 'image'" :src="`/storage/uploads/${item.story_id}/${item.path_name}`"
                         class="circle-img" alt="">
                    <img v-else :src=" `/img/vid-icon.jpg`" class="circle-img" alt="">
                </div>
            </div>
            <div v-for="(file, key) in files" class="file-listing">
                <p>
                    <span class="remove-file" v-on:click="removeFile( key )">
                        <img src="/img/garbage.png" alt="">
                      </span>
                    {{file.name}}
                </p>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="save-btn">Save</button>
            </div>
        </form>
    </div>
</template>
<script>
    import storiesApi from '../api/stories';
    export default {
        name: 'AddEditStory',
        data() {
            return {
                story: {
                    id: null,
                    page_name: "",
                    items: [],
                },
                files: [],
            };
        },
        methods: {
            onSubmit() {
                /*
                  Initialize the form data
                */
                let formData = new FormData();
                if (this.story.id !== null) {
                    formData.append('id', this.story.id);
                }
                formData.append('page_name', this.story.page_name);
                /*
                  Iterate over any file sent over appending the files
                  to the form data.
                */
                for (let i = 0; i < this.files.length; i++) {
                    let file = this.files[i];

                    formData.append('files' + i, file);
                }

                /*
                  Make the request to the POST /multiple-files URL
                */
                storiesApi.saveItems(formData)
                    .then(res => {
                        this.$router.push('/'); // redirect to stories page
                    })
                    .catch(err => {
                        console.log('FAILURE!!', err);
                    });

            },
            /*
              Handles a change on the file upload
            */
            handleFilesUpload() {
                let uploadedFiles = this.$refs.files.files;

                /*
                  Adds the uploaded file to the files array
                */
                for( let i = 0; i < uploadedFiles.length; i++ ){
                    this.files.push( uploadedFiles[i] );
                }
            },
            addFiles() {
                this.$refs.files.click();
            },
            removeFile(key) {
                this.files.splice(key, 1);
            },
            removeFileItem(key) {
                const item = this.story.items[key];
                storiesApi.removeItem(item.id)
                    .then(res => {
                        if (res.data) this.story.items.splice(key, 1);
                    })
                    .catch(err => {
                        console.log('storiesApi', err);
                    });
            }
        },
        created() {
            if (this.$route.params.id !== undefined) {
                this.story.id = this.$route.params.id;
                storiesApi.find(this.$route.params.id)
                    .then(res => {
                        this.story = res.data;
                    })
                    .catch(err => {
                        console.log('storiesApi', err);
                    });
            } else {

            }
        },
        beforeRouteLeave (to, from, next) {
            // called when the route that renders this component is about to
            // be navigated away from.
            // has access to `this` component instance.
            if (to.name === 'stories.add') {
                this.story = {
                    id: null,
                    page_name: "",
                    items: [],
                };
                this.files = [];
            }
            next();
        }
    };
</script>
<style scoped>
    input[type="file"] {
        position: absolute;
        top: -500px;
    }

    .remove-file {
        cursor: pointer;
        color: red;
    }
</style>
