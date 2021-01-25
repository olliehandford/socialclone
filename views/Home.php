<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Header.php';
?>
<section class="section">
<div class="container social-container is-max-desktop">
<?php if(isset($_SESSION['authenticated'])){?>

<h1 class="title">Welcome back, <?php echo $_SESSION['username']; ?></h1>


<div id="app">
    <div class="notification is-primary" v-show="success">
        {{ successMessage }}
    </div>

    <div class="notification is-danger" v-show="error">
        {{ errorMessage }}
    </div>


    <div class="box" v-if="!isPostingPicture">
        <div class="media">
            <div class="media-content">
                <div class="field">
                    <textarea class="textarea is-primary" placeholder="Post your thoughts..." v-model="postThread.message"></textarea>
                </div>
                <div class="field">
                    <button class="button is-primary is-fullwidth" @click="submitPost()">Post</button>
                </div>
                Or post a <a @click="isPostingPic">picture</a>
            </div>
        </div>
    </div>
    <div class="box" v-else>
        <div class="media">
            <div class="media-content">
                <div class="field">
                    <textarea class="textarea is-primary" placeholder="Post your thoughts..." v-model="postPicture.message"></textarea>
                </div>
                <div class="field">
                    <div class="file has-name is-boxed" :class="{ 'is-success': isUploaded }" >
                    <label class="file-label" style="width: 100%;">
                        <input class="file-input" type="file" @change="onPictureChangedEvent" accept="image/*">
                        <span class="file-cta">
                                  <span class="file-icon">
                                    <i class="fas fa-upload"></i>
                                </span>
                        <span class="file-label">
                            Choose a file…
                        </span>
                        </span>
                        <p class="help" v-show="!isUploaded">Only accepts valid image files (e.g .png, .jpeg, .gif)</p>
                        <p class="help" v-show="isUploaded">File has been uploaded!</p>
                    </label>
                    
                    </div>
                                    </div>
                    <div class="field">
                    <input type="text" class="input is-primary" placeholder="Image tags seperated by commas (e.g House, Home, Building)" v-model="postPicture.tags" />
                    
                </div>
                <div class="field">
                    <button class="button is-primary is-fullwidth" @click="submitPicturePost()">Post</button>
                </div>
                 Or post a <a @click="isPostingPic">text post</a>
            </div>
        </div>
    </div>

    <div class="box" v-for="(post, index) in posts">
    <article class="media">
        <div class="media-left is-hidden-mobile is-hidden-tablet-only">
        <figure class="image is-64x64">
            <img :src="post.user.avatar" alt="Image">
        </figure>
        </div>
        <div class="media-content">
        <div class="content">
            <p>
            <strong><a :href="'/profile?user=' + post.user.username">{{ post.user.username }}</a></strong> <small>@{{ post.user.username }}</small> <small>{{ getHumanTime(post.timestamp) }}</small>
            <br>
                {{ post.content }}
                <div v-if="post.isPicture == 1">
                    <img :src="post.pictureData" />
                </div>

                <div v-if="post.replies.length != 0">
                <h4>Replies</h4>
                <article class="media" v-for="(reply, index) in post.replies">
                    <div class="media-left is-hidden-mobile is-hidden-tablet-only">
                    <figure class="image is-64x64">
                        <img :src="reply.user.avatar" alt="Image">
                    </figure>
                    </div>
                    <div class="media-content">
                    <div class="content">
                        <p>
                        <strong><a :href="'/profile?user=' + reply.user.username">{{ reply.user.username }}</a></strong> <small>@{{ reply.user.username }}</small> <small>{{ getHumanTime(reply.timestamp) }}</small>
                        <br>
                            {{ reply.content }}
                        </p>

                    <nav class="level is-mobile">
                        <div class="level-left">
                                        <a class="level-item" aria-label="like" @click="likePost(reply.id)" v-show="reply.owner != authUserID">
                            <span class="icon is-small">
                            <i class="fas fa-heart" aria-hidden="true"></i> 
                            </span>
                        </a>
                        <div class="level-item" v-show="reply.owner != authUserID">
                            ({{ reply.likes }})
                        </div>
                        <a class="level-item" aria-label="delete" @click="deletePost(reply.id)" v-show="reply.owner == authUserID">
                            <span class="icon is-small">
                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                            </span>
                        </a>
                        </div>
                        </div>
                        
                    </div>
                    </article>
                </div>
            </p>
            <div v-show="replies">
            <div class="media">
                <div class="media-content">
                    <div class="field">
                        <input class="input is-primary" placeholder="Post your thoughts..." v-model="postReply.message"></textarea>
                    </div>
                    <div class="field">
                        <button class="button is-primary is-fullwidth" @click="submitPostReply(post.id)">Post</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <nav class="level is-mobile">
            <div class="level-left">
            <a class="level-item" aria-label="reply" @click="replies = !replies">
                <span class="icon is-small">
                <i class="fas fa-reply" aria-hidden="true" ></i>
                </span>
            </a>
            <a class="level-item" aria-label="like" @click="likePost(post.id)" v-show="post.owner != authUserID">
                <span class="icon is-small">
                <i class="fas fa-heart" aria-hidden="true"></i> 
                </span>
            </a>
            <div class="level-item" v-show="post.owner != authUserID">
                ({{ post.likes }})
            </div>
            <a class="level-item" aria-label="delete" @click="deletePost(post.id)" v-show="post.owner == authUserID">
                <span class="icon is-small">
                <i class="fas fa-trash-alt" aria-hidden="true"></i>
                </span>
            </a>
            

            </div>
            <div class="level-right">
                <div v-if="post.isPicture == 1">
                    <div class="tags">
                        <span class="tag" v-for="(tag, index) in getTagsArray(post.pictureTags)" >{{ tag }}</span>
                    </div>
                </div>
            </div>
        </nav>
        </div>
    </article>
    </div>
    <a class="button is-primary is-fullwidth" @click="loadMorePosts()" v-show="!showingAll" style="margin-bottom: 20px;">Load more</a>
</div>

<script>




var app = new Vue({
    el: '#app',
    data: {
        isPostingPicture: false,
        posts: null,
        showingAll: false,
        success: false,
        successMessage: null,
        error: false,
        errorMessage: null,
        isUploaded: false,
        authUserID: <?php echo $_SESSION['id']; ?>,
        postThread: {
            message: null,
            apiKey: '<?php echo $_SESSION['apiKey']; ?>'
        },
        postPicture: {
            apiKey: '<?php echo $_SESSION['apiKey']; ?>',
            message: null,
            image: null,
            tags: null
        },
        postReply: {
            apiKey: '<?php echo $_SESSION['apiKey']; ?>',
            message: null,
            postid: null
        },
        replies: false
    },
    methods : {
        isPostingPic() {
            this.isPostingPicture = !this.isPostingPicture
        },
        getHumanTime(time)
        {
            return moment.unix(time).format("MM/DD/YYYY")
        },
        loadMorePosts()
        {
            var currentPostCount = this.posts.length
            axios.get('/getPosts?currentPosts=' + currentPostCount).then(response => {
                for (i = 0; i < response.data.length; i++) {
                    this.posts.push(response.data[i])
                }
                if(response.data == '') {
                    this.showingAll = true
                }
            })
        },
        loadPosts()
        {
            axios.get('/getPosts').then(response => {
                this.posts = response.data
            })
        },
        submitPost()
        {
            axios.post('/post', this.postThread)
            .then(function (response) {
                this.success = true
                this.error = false
                this.successMessage = response.data.message
                this.loadPosts()
            }.bind(this))
            .catch(function (error) {
                this.error = true
                this.errorMessage = error.response.data.message
            }.bind(this))
        },
        onPictureChangedEvent (event) {
           this.getBase64(event.target.files[0])
        },
        getBase64(file) {
            var reader = new FileReader()
            reader.readAsDataURL(file)
            reader.onload = function () {
                //console.log(reader.result)
                this.postPicture.image = reader.result
                this.isUploaded = true
            }.bind(this)
            reader.onerror = function (error) {
                console.log('Error: ', error)
            }
        },
        submitPicturePost()
        {
            axios.post('/postImage', this.postPicture)
            .then(function (response) {
                this.success = true
                this.error = false
                this.successMessage = response.data.message
                console.log(response)
                this.loadPosts()
            }.bind(this))
            .catch(function (error) {
                this.error = true
                this.errorMessage = error.response.data.message
            }.bind(this))
        },
        getTagsArray(tagString)
        {
            return tagString.split(",")
        },
        likePost(postid)
        {
            var data = {
                apiKey: this.postPicture.apiKey,
                postid: postid 
            }
            axios.post('/likePost', data)
            .then(function (response) {
                this.success = true
                this.error = false
                this.successMessage = response.data.message
                this.loadPosts()
                console.log(response)
            }.bind(this))
            .catch(function (error) {
                this.error = true
                this.errorMessage = error.response.data.message
            }.bind(this))
        },
        deletePost(postid)
        {
            var data = {
                apiKey: this.postPicture.apiKey,
                postid: postid 
            }
            axios.post('/deletePost', data)
            .then(function (response) {
                this.success = true
                this.error = false
                this.successMessage = response.data.message
                this.loadPosts()
                console.log(response)
            }.bind(this))
            .catch(function (error) {
                this.error = true
                this.errorMessage = error.response.data.message
            }.bind(this))
        },
        submitPostReply(id)
        {
            this.postReply.postid = id
            console.log(this.postReply.postid)
            axios.post('/replyToPost', this.postReply)
            .then(function (response) {
                this.success = true
                this.error = false
                this.successMessage = response.data.message
                this.loadPosts()
            }.bind(this))
            .catch(function (error) {
                this.error = true
                this.errorMessage = error.response.data.message
            }.bind(this))
        }
    },
    mounted() {
        this.loadPosts()
    }
})
</script>

<?php } else{ ?>

<h1 class="title">Welcome to Social Network.</h1>
<h2 class="subtitle"><a href="/login">Login</a> or <a href="/register">Register</a></h2>

<?php } ?>

</div>
</section>
<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Footer.php';
?>