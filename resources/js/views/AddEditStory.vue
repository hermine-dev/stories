<template>
    <div>
        <form @submit.prevent="onSubmit($event)">
            <div class="form-group">
                <label for="page_name">Page Name:</label>
                <input id="page_name" v-model="story.page_name"/>
            </div>
            <div class="form-group">
                <label>Files
                    <input type="file" id="files" ref="files" multiple v-on:change="handleFilesUpload"/>
                </label>
            </div>
            <div class="form-group">
                <button type="button" v-on:click="addFiles()">+</button>
            </div>
            <div class="form-group">
                <div v-for="(item, keyItem) in story.items" class="file-listing"><span class="remove-file" v-on:click="removeFileItem( keyItem )">x</span>    {{ item.original_name }}</div>
                <div v-for="(file, key) in files" class="file-listing"><span class="remove-file" v-on:click="removeFile( key )">x</span>    {{ file.name }}</div>
            </div>
            <div class="form-group">
                <button type="submit">Save</button>
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
                files: []
            };
        },
        methods: {
            onSubmit(event) {
                /*
                  Initialize the form data
                */
                let formData = new FormData();
                if(this.story.id !== null) {
                    formData.append('id', this.story.id);
                }
                formData.append('page_name', this.story.page_name);
                /*
                  Iterate over any file sent over appending the files
                  to the form data.
                */
                for( let i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];

                    formData.append('files' + i, file);
                }

                /*
                  Make the request to the POST /multiple-files URL
                */
                storiesApi.saveItems(formData)
                    .then(res => {
                        console.log('SUCCESS!!', res);
                    })
                    .catch(err => {
                        console.log('FAILURE!!', err);
                    });

            },
            /*
              Handles a change on the file upload
            */
            handleFilesUpload(){
                let uploadedFiles = this.$refs.files.files;

                /*
                  Adds the uploaded file to the files array
                */
                for( let i = 0; i < uploadedFiles.length; i++ ){
                    this.files.push( uploadedFiles[i] );
                }
            },
            addFiles(){
                this.$refs.files.click();
            },
            removeFile( key ){
                this.files.splice( key, 1 );
            },
            removeFileItem( key ){
                const item = this.story.items[key];
                storiesApi.removeItem(item.id)
                    .then(res => {
                        console.log('remove Item', res);
                        if(res.data) this.story.items.splice( key, 1 );
                    })
                    .catch(err => {
                        console.log('storiesApi', err);
                    });
            }
        },
        created() {
            if(this.$route.params.id !== undefined){
                this.story.id = this.$route.params.id;
                storiesApi.find(this.$route.params.id)
                                .then(res => {
                                    console.log('storiesApi', res);
                                    this.story = res.data;
                                })
                                .catch(err => {
                                    console.log('storiesApi', err);
                                });
            }
        }
    };
</script>
<style scoped>
    input[type="file"]{
        position: absolute;
        top: -500px;
    }
    .remove-file{
        cursor: pointer;
        color: red;
    }
</style>
