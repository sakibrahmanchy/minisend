<template>
  <div>
    <div v-if="loading">
      <Loader />
    </div>
    <div v-else>
      <div class="col-sm-12">
        <!-- Star form compose mail -->
        <form v-if="mail" class="form-horizontal">
          <div class="panel mail-wrapper rounded shadow p-2">
            <div class="panel-heading ">
              <div class="clearfix" />
            </div><!-- /.panel-heading -->
            <div class="panel-sub-heading inner-all">
              <div class="pull-left">
                <h3 class="lead no-margin">
                  {{ mail.subject }}
                </h3>
              </div>
              <div class="clearfix" />
            </div><!-- /.panel-sub-heading -->
            <div class="panel-sub-heading inner-all">
              <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-7">
                  <img :src="'http://placehold.it/200x200?text='+mail.from[0]" alt="..." class="img-circle senden-img">
                  <span>{{ mail.from }}</span>
                  to
                  <strong>{{ mail.to }}</strong>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-5">
                  <p class="pull-right">
                    {{ mail.created_at }}
                  </p>
                </div>
              </div>
            </div><!-- /.panel-sub-heading -->
            <div class="panel-body">
              <div class="view-mail" v-html="mail.content">
                {{ mail.content }}
              </div><!-- /.view-mail -->
              <div class="attachment-mail">
                <ul>
                  <li v-for="attachment of mail.attachments" :key="attachment.id">
                    <a class="atch-thumb" href="#" data-toggle="modal" data-target=".bs-example-modal-photo1">
                      <img :src="attachment.url" alt="..." onerror=" this.src = `http://placehold.it/200x200?text=file`; this.onerror = ''">
                    </a>

                    <a class="name" href="#">
                      {{ attachment.name }}
                      <span>{{ attachment.size }}</span>
                    </a>

                    <div class="links">
                      <a :href="attachment.url">Download</a>
                    </div>
                  </li>
                </ul>
              </div><!-- /.attachment mail -->
            </div><!-- /.panel-body -->
            <div class="panel-footer">
              <div class="pull-right p-4">
                <router-link :to="{ name: 'home' }">
                  <button class="btn btn-primary">
                    Back
                  </button>
                </router-link>
              </div>
              <div class="clearfix" />
            </div><!-- /.panel-footer -->
          </div><!-- /.panel -->
        </form>
        <!--/ End form compose mail -->
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Card from '../../components/global/Card'
import Loader from '../../components/global/Loader'

export default {
  name: 'MailDetails',
  components: { Loader, Card },
  data: () => ({
    loading: false,
    mail: null
  }),
  mounted () {
    this.fetchDetails()
  },
  methods: {
    fetchDetails () {
      const mailId = this.$route.params.id
      if (mailId) {
        this.loading = true
        axios.get(`mail/${mailId}`).then((res) => {
          this.loading = false
          const {
            data: {
              data = null
            } = {}
          } = res
          this.mail = data
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.senden-img{
  width:50px;
  height:50px;
}

.btn-compose-email {
  padding: 10px 0px;
  margin-bottom: 20px;
}

.btn-danger {
  background-color: #E9573F;
  border-color: #E9573F;
  color: white;
}

.panel-teal .panel-heading {
  background-color: #37BC9B;
  border: 1px solid #36b898;
  color: white;
}

.panel .panel-heading {
  padding: 5px;
  border-top-right-radius: 3px;
  border-top-left-radius: 3px;
  -moz-border-radius: 0px;
  -webkit-border-radius: 0px;
  border-radius: 0px;
}

.panel .panel-heading .panel-title {
  padding: 10px;
  font-size: 17px;
}

form .form-group {
  position: relative;
  margin-left: 0px !important;
  margin-right: 0px !important;
}

.inner-all {
  padding: 10px;
}

/* ========================================================================
 * MAIL
 * ======================================================================== */
.nav-email > li:first-child + li:active {
  margin-top: 0px;
}
.nav-email > li + li {
  margin-top: 1px;
}
.nav-email li {
  background-color: white;
}
.nav-email li.active {
  background-color: transparent;
}
.nav-email li.active .label {
  background-color: white;
  color: black;
}
.nav-email li a {
  color: black;
  -moz-border-radius: 0px;
  -webkit-border-radius: 0px;
  border-radius: 0px;
}
.nav-email li a:hover {
  background-color: #EEEEEE;
}
.nav-email li a i {
  margin-right: 5px;
}
.nav-email li a .label {
  margin-top: -1px;
}

.table-email tr:first-child td {
  border-top: none;
}
.table-email tr td {
  vertical-align: top !important;
}
.table-email tr td:first-child, .table-email tr td:nth-child(2) {
  text-align: center;
  width: 35px;
}
.table-email tr.unread, .table-email tr.selected {
  background-color: #EEEEEE;
}
.table-email .media {
  margin: 0px;
  padding: 0px;
  position: relative;
}
.table-email .media h4 {
  margin: 0px;
  font-size: 14px;
  line-height: normal;
}
.table-email .media-object {
  width: 35px;
  -moz-border-radius: 2px;
  -webkit-border-radius: 2px;
  border-radius: 2px;
}
.table-email .media-meta, .table-email .media-attach {
  font-size: 11px;
  color: #999;
  position: absolute;
  right: 10px;
}
.table-email .media-meta {
  top: 0px;
}
.table-email .media-attach {
  bottom: 0px;
}
.table-email .media-attach i {
  margin-right: 10px;
}
.table-email .media-attach i:last-child {
  margin-right: 0px;
}
.table-email .email-summary {
  margin: 0px 110px 0px 0px;
}
.table-email .email-summary strong {
  color: #333;
}
.table-email .email-summary span {
  line-height: 1;
}
.table-email .email-summary span.label {
  padding: 1px 5px 2px;
}
.table-email .ckbox {
  line-height: 0px;
  margin-left: 8px;
}
.table-email .star {
  margin-left: 6px;
}
.table-email .star.star-checked i {
  color: goldenrod;
}

.nav-email-subtitle {
  font-size: 15px;
  text-transform: uppercase;
  color: #333;
  margin-bottom: 15px;
  margin-top: 30px;
}

.compose-mail {
  position: relative;
  padding: 15px;
}
.compose-mail textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #DDD;
}

.view-mail {
  padding: 10px;
  font-weight: 300;
}

.attachment-mail {
  padding: 10px;
  width: 100%;
  display: inline-block;
  margin: 20px 0px;
  border-top: 1px solid #EFF2F7;
}
.attachment-mail p {
  margin-bottom: 0px;
}
.attachment-mail ul {
  padding: 0px;
}
.attachment-mail ul li {
  float: left;
  width: 200px;
  margin-right: 15px;
  margin-top: 15px;
  list-style: none;
}
.attachment-mail ul li a.atch-thumb img {
  width: 200px;
  margin-bottom: 10px;
}
.attachment-mail ul li a.name span {
  float: right;
  color: #767676;
}

@media (max-width: 640px) {
  .compose-mail-wrapper .compose-mail {
    padding: 0px;
  }
}
@media (max-width: 360px) {
  .mail-wrapper .panel-sub-heading {
    text-align: center;
  }
  .mail-wrapper .panel-sub-heading .pull-left, .mail-wrapper .panel-sub-heading .pull-right {
    float: none !important;
    display: block;
  }
  .mail-wrapper .panel-sub-heading .pull-right {
    margin-top: 10px;
  }
  .mail-wrapper .panel-sub-heading img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 10px;
  }
  .mail-wrapper .panel-footer {
    text-align: center;
  }
  .mail-wrapper .panel-footer .pull-right {
    float: none !important;
    margin-left: auto;
    margin-right: auto;
  }
  .mail-wrapper .attachment-mail ul {
    padding: 0px;
  }
  .mail-wrapper .attachment-mail ul li {
    width: 100%;
  }
  .mail-wrapper .attachment-mail ul li a.atch-thumb img {
    width: 100% !important;
  }
  .mail-wrapper .attachment-mail ul li .links {
    margin-bottom: 20px;
  }

  .compose-mail-wrapper .search-mail input {
    width: 130px;
  }
  .compose-mail-wrapper .panel-sub-heading {
    padding: 10px 7px;
  }
}
</style>
