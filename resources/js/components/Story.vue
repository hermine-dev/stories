<template>
    <div class="story">
        <div class="timeline">
            <div class="slice" v-for="(slide, i) in slides" :key="i">
                <div class="progress">&nbsp;</div>
            </div>
        </div>
        <div class="slide">
            <div class="row">
                <div class="col-md-3">
                    <div width="80px">
                        <img src="/img/avatar.png" alt="avatar" width="100%">
                    </div>
                    <div><strong>{{$props.story.page_name}}</strong>
                        <router-link :to="{ name: 'stories.edit', params: {id: $props.story.id } }">Edit</router-link>
                    </div>
                    <div>8m ago</div>
                </div>
                <div class="col-md-9">
                    <div v-if="slides[currentSlideIndex].type === 'image'" width="250">
                        <img :src="`/storage/uploads/${$props.story.id}/${slides[currentSlideIndex].path_name}`"
                             :alt="slides[currentSlideIndex].original_name" width="100%">
                    </div>
                    <div v-if="slides[currentSlideIndex].type === 'video'">
                        <video src="" :id="`video-${$props.story.id}-${slides[currentSlideIndex].id}`" controls></video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import anime from 'animejs/lib/anime.min';
    import Hammer from 'hammerjs';
    import {EventBus} from '../helpers/EventBus';

    const SLIDE_DURATION = 5000;

    export default {
        name: 'Story',
        props: {
            story: Object
        },
        data() {
            const timeline = anime.timeline({
                autoplay: true,
                loop: true,
                duration: SLIDE_DURATION,
                easing: 'linear'
            });

            return {
                currentSlideIndex: 0,
                isActive: false,
                timeline: timeline,
                slides: []
            }
        },
        methods: {
            activate: function () { // Start timer
                this.resetSlide();
            },
            deactivate: function () {
                this.timeline.pause();
            },
            resetSlide: function () { // Jump to beginning of the slide
                this.timeline.pause();
                this.timeline.seek(this.currentSlideIndex * SLIDE_DURATION);
                this.timeline.play();
            },
            nextSlide: function () {
                if (this.currentSlideIndex < this.slides.length - 1) {
                    this.currentSlideIndex++;
                    this.resetSlide();
                } else {
                    this.nextStory();
                }
            },
            previousSlide: function () {
                if (this.currentSlideIndex > 0) {
                    this.currentSlideIndex--;
                    this.resetSlide();
                } else {
                    this.previousStory();
                }
            },
            nextStory: function () {
                EventBus.$emit('NEXT_STORY');
            },
            previousStory: function () {
                EventBus.$emit('PREVIOUS_STORY');
            }
        },
        mounted() {
            let $timeline = this.$el.getElementsByClassName('timeline')[0];

            // Add progress bars to the timeline animation group
            this.slides.forEach((color, index) => {
                this.timeline.add({
                    targets: $timeline.getElementsByClassName('slice')[index].getElementsByClassName('progress'),
                    width: '100%',
                    changeBegin: () => {
                        // Update the Vue component state when progress bar begins to play
                        this.currentSlideIndex = index;
                        if (this.slides[index].type === 'video') {
                            let video = document.getElementById(`video-${this.$props.story.id}-${this.slides[index].id}`);
                            video.src = `/storage/uploads/${this.$props.story.id}/${this.slides[index].path_name}`;
                            //video.play(); // todo need to check correct time to play
                        }

                    },
                    complete: () => {
                        // Move to the next story when finished playing all slides
                        if (index === this.slides.length - 1) {
                            this.nextStory();
                        }
                    }
                });
            });

            this.hammer = new Hammer.Manager(this.$el, {
                recognizers: [
                    [Hammer.Pan, {direction: Hammer.DIRECTION_HORIZONTAL}],
                    [Hammer.Tap],
                    [Hammer.Press, {time: 1, threshold: 1000000}]
                ]
            });

            this.hammer.on("press", () => {
                this.timeline.pause();
            });

            this.hammer.on("pressup tap", () => {
                this.timeline.play();
            });

            // Tap on the side to navigate between slides
            this.hammer.on("tap", event => {
                if (event.center.x > window.innerWidth / 3) {
                    this.nextSlide();
                } else {
                    this.previousSlide();
                }
            });

            // Handle swipe
            this.hammer.on("pan", event => {
                if (event.isFinal) {
                    if (event.deltaX < 0) {
                        this.nextStory();
                    } else if (event.deltaX > 0) {
                        this.previousStory();
                    }
                }
            });
        },
        created() {
            this.slides = this.$props.story.items;
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .story {
        float: left;
        position: relative;
        height: 100vh;
        width: 100vw;
        z-index: 1;
        display: flex;
        flex-direction: column;
    }

    .timeline {
        display: flex;
        flex-grow: 0;
        width: 100%;
    }

    .timeline > .slice {
        background: rgba(0, 0, 0, 0.25);
        height: 10px;
        margin: 10px;
        width: 100%;
    }

    .timeline > .slice > .progress {
        background: black;
        height: 10px;
        width: 0%;
    }

    .slide {
        /* Take the rest of the page */
        flex-grow: 1;

        /* Center align */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slide p {
        font-size: 60px;
        opacity: .5;
    }
</style>
