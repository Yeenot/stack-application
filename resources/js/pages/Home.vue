<template>
    <div>
        <div class="actions">
            <b-button @click="generate()">Generate</b-button>
            <b-button v-b-modal.modal-1>Add new image</b-button>

            <b-modal id="modal-1">
                <h6 slot="modal-title">Add New Image</h6>
                
                <div class="form-group">
                    <label>Array index:</label>
                    <input class="form-control" type="text" v-model="form.index">
                </div>
                <div class="form-group">
                    <label>Image file:</label>
                    <b-form-file
                        v-model="form.image"
                        :state="Boolean(form.image)"
                        placeholder="Choose a file or drop it here..."
                        drop-placeholder="Drop file here..."
                    ></b-form-file>
                    <div class="mt-3">Selected file: {{ form.image ? form.image.name : '' }}</div>
                </div>
                <div slot="modal-footer">
                    <b-button variant="success" @click="addImage()">Add</b-button>
                </div>
            </b-modal>

            <b-modal id="modal-2" size="xl">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4" v-for="(item, index) in selectedItems">
                            <img
                                v-if="item.name"
                                :src="'/storage/'+item.path"
                                :alt="item.name"
                                :style="getStaticImgStyle"
                            />
                            <div
                                v-else
                                :style="getStaticImgStyle"
                            >
                            </div>
                        </div>
                    </div>
                </div>
                 <div slot="modal-footer">
                    <b-button variant="danger" @click="close()">Close</b-button>
                </div>
            </b-modal>
        </div>
        <div class="stacks container-fluid mt-4">
            <div class="row">
                <div class="col-4" v-for="(stack, index) in stacks" :key="index" :style="getColumnStyle">
                    <div
                        class="img-container position-relative"
                        :style="getContainerStyle"
                        @click="() => onItemClick(index)"
                    >
                        <template v-for="(item, index2) in stack">
                            <img
                                v-if="item.name"
                                class="position-absolute"
                                :src="'/storage/'+item.path"
                                :alt="item.name"
                                :style="getImgStyle(index2)"

                            />
                            <div
                                v-else
                                class="position-absolute"
                                :style="getImgStyle(index2)"
                            >
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        form: {
            index: null,
            image: null
        },
        stacks: [],
        selected: null
    }),
    mounted() {
        this.getStacks()
    },
    computed: {
        selectedItems() {
            return this.stacks[this.selected];
        },
        getContainerStyle() {
            return {
                'width': '200px',
                'height': '200px'
            }
        },
        getColumnStyle() {
            return {
                'margin-bottom': '100px',
            }
        },
        getStaticImgStyle() {
            return {
                'border': '1px dashed #000000',
                'width': '200px',
                'height': '200px',
                'backgroundColor': '#e0e6ed'
            }
        }
    },
    methods: {
        getImgStyle(index) {
            var distance = `${10*index}px`

            return {
                'border': '1px dashed #000000',
                'width': '200px',
                'height': '200px',
                'backgroundColor': '#e0e6ed',
                'left': distance,
                'top': distance
            }
        },
        generate() {
            axios.post('/api/stacks/generate')
                .then((response) => {
                    if (response) {
                        this.getStacks()
                    }
                })
        },
        getStacks() {
            axios.get('/api/stacks')
                .then((response) => {
                    if (response) {
                        this.stacks = response.data
                    }
                })
        },
        addImage() {
            this.$bvModal.hide('modal-1')
            let formData = new FormData()
            formData.append("index", this.form.index)
            formData.append("image", this.form.image)

            axios.post('/api/stacks', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    this.getStacks()
                })
        },
        onItemClick(index) {
            this.selected = index
            this.$bvModal.show('modal-2')
        },
        close() {
            this.$bvModal.hide('modal-2')
        }
    }
}
</script>