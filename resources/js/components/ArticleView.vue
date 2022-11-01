<template>

  <div v-for="article in articles">
    <div class="container border-bottom border-1">
    <div class="row">
        <div class="col-md-8 ">
            <div class="card-body">
                <h2 class="card-title">{{article.title}}</h2>
                <a v-bind:href="article.title_link" class="card-link"><p class="card-text">{{article.excerpt}}</p></a>
                <div class="card-footer">
                    <small class="text-muted">{{article.date}}</small>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <img class="img-fluid"  :src="`${article.image_url}`">

        </div>
    </div>

</div>

    </div>

</template>

<script>

export default {
    data() {
        return {
            articles: []
        }
    },
    methods: {
        getArticles() {
            axios.get('/api/articles')
                .then((response) => {
                    this.articles = response.data.data
                })
        }
    },
    mounted() {
        this.getArticles()
    }
  }
</script>
