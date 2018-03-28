<template>
    <div class="plonk">
        <input :type="showHashInput ? 'text' : 'hidden'" :name="hashInputName" :value="imageHash" class="hash-input form-control">
        <img v-if="showPreviewImage && previewSrc" :src="previewSrc" class="preview-image">
        <button class="btn btn-primary" @click.prevent="openImageLibrary">{{ openImageLibraryButtonText }}</button>
        <button v-show="imageHash || previewSrc" class="btn btn-secondary" @click.prevent="removeImage">{{ removeImageButtonText }}</button>
        <div class="modal fade" id="plonk-modal" tabindex="-1" role="dialog" aria-labelledby="Image Library" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h5 class="modal-title">{{ modalTitle }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form v-if="showSearch" @submit.prevent="searchImages" class="form-inline search-form">
                            <input class="form-control" type="text" placeholder="Search..." v-model="searchInput">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                            <a href="#" @click.prevent="clearSearch" v-show="this.search"><i class="fa fa-times"></i> Clear search</a>
                        </form>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4 card-container" v-for="(image, index) in images">
                                <div class="card">
                                    <div class="card-header">
                                        {{ image.title }}
                                    </div>
                                    <div class="card-body">
                                        <img class="card-img-top" :src="imagePath + image.resource.smallest">
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <a href="#" @click.prevent="insertImage(image)">
                                                <i aria-hidden="true" class="fa fa-download"></i>
                                                Insert
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>

.modal-header {
    flex-wrap: wrap;

    .modal-title {
        align-items: flex-start;
        display: flex;
        width: 100%;
    }
}
.card-container {
    display: flex;

    .card-body {
        align-items: center;
        display: flex;
    }
}

.search-form {
    margin-top: .3em;

    input, button {
        margin-right: .5em;
    }
}

.hash-input {
    margin-bottom: 1em;
}

.preview-image {
    display: block;
    margin-bottom: 1em;
    max-height: 150px;
    max-width: 150px;
}

</style>

<script>

export default {

    props: {
        apiPath: {
            type: String,
            default: '/api/plonk',
        },
        hashInputName: {
            type: String,
            default: 'image'
        },
        hashInputValue: {
            type: String,
            default: ''
        },
        imagePath: {
            type: String,
            default: '',
        },
        modalTitle: {
            type: String,
            default: 'Image Library'
        },
        openImageLibraryButtonText: {
            type: String,
            default: 'Select Image'
        },
        previewImageSrc: {
            type: String,
            default: ''
        },
        removeImageButtonText: {
            type: String,
            default: 'Remove Image'
        },
        showHashInput: {
            type: Boolean,
            default: false
        },
        showPreviewImage: {
            type: Boolean,
            default: true
        },
        showSearch: {
            type: Boolean,
            default: true
        },
    },

    data() {
        return {
            images: [],
            search: undefined,
            searchInput: undefined,
            page: 1,
            previewSrc: this.previewImageSrc,
            imageHash: this.hashInputValue,
        }
    },

    methods: {

        openImageLibrary() {
            this.initImages();
            $('#plonk-modal').modal('show');
        },

        initImages() {
            this.page = 1;
            this.images = [];
            this.getImages();
        },

        getImages() {
            axios.get(this.apiPath, {
                params: {
                    search: this.search,
                    page: this.page
                }
            })
            .then(response => {
                this.images.push(...response.data.data);
                if(this.page < response.data.last_page) {
                    document.getElementById('plonk-modal').addEventListener('scroll', this.onScroll);
                }
                this.page++
            });
        },

        searchImages() {
            this.search = this.searchInput;
            this.initImages();
        },

        clearSearch() {
            this.search = this.searchInput = undefined;
            this.initImages();
        },

        insertImage(image) {
            this.imageHash = image.hash;
            this.previewSrc = this.imagePath + image.resource.smallest;
            $('#plonk-modal').modal('hide');
        },

        removeImage() {
            this.imageHash = this.previewSrc = undefined;
        },

        onScroll() {
            let modalScroll = document.getElementById('plonk-modal').scrollTop + document.getElementById('plonk-modal').offsetHeight;
            let modalHeight = document.getElementsByClassName('modal-content')[0].offsetHeight;

            if (modalScroll >= modalHeight) {
                document.getElementById('plonk-modal').removeEventListener('scroll', this.onScroll);
                this.getImages();
            }
        },
    },
}
</script>
