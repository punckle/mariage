<template>
  <div id="app">
    <div class="position-relative overflow-hidden text-center">
      <div v-if="!lost" class="p-lg-5 mx-auto my-2">
        <div class="row">
          <div class="col text-center mb-4">
            Vous êtes invité au mariage de Manon & Xavier ? <br><br>
            Indiquez le code figurant sur votre invitation puis répondez aux questions posées <i class="far fa-smile"></i>
          </div>
        </div>
          <div class="row" v-if="validCode === null">
            <div class="col text-center my-4">
              <input v-model="code" type="text">
            </div>
          </div>
          <div class="text-center" v-if="validCode === null">
            <button class="btn btn-warning contact my-3" v-on:click="lostCode()">Code perdu</button>
            <button class="btn btn-success contact my-3" v-on:click="sendCode(code)">Valider</button>
          </div>

          <div v-if="validCode === false">
            <div class="alert alert-danger my-3">
              Oups ! Soit : <br>
              - Ce code a déjà été utilisé <br>
              - Ce code n'existe pas <br>
              En cas de problème, n'hésitez pas à contacter Manon & Xavier. <br>
            </div>
          </div>

          <div v-if="validCode === true">
            <div class="alert alert-success my-3">
              Super, vous faites bien parti des invités ! Merci de répondre aux questions suivantes
            </div>

            <hr>

            <div class="row">
              <div class="col text-center my-2">
                <strong>Venez-vous au mariage de Manon & Xavier ?</strong>
              </div>
            </div>
            <div class="row">
              <div class="offset-5 col-1 text-center">
                <button class="btn btn-success mt-2 contact" v-on:click="updateIsPresent(true)">Oui</button>
              </div>
              <div class="col-1 text-center">
                <button class="btn btn-danger mt-2 contact" v-on:click="updateIsPresent(false)">Non</button>
              </div>
            </div>

            <hr>

            <div v-if="form.isPresent === false">
              <div class="row">
                <div class="col text-center my-2">
                  Nous sommes désolés de ne pas vous voir à notre mariage. Si vous le souhaitez, n'hésitez pas à nous
                  laisser
                  un petit mot ici :
                </div>
              </div>
              <div class="row">
                <div class="col text-center my-2">
                  <textarea v-model="form.message" cols="50" rows="10"></textarea>
                </div>
              </div>
            </div>

            <div v-if="form.isPresent === true">
              <div class="row">
                <div class="col text-center my-2">
                  Combien serez-vous (en vous incluant) ?
                </div>
              </div>
              <div class="row">
                <div class="col text-center my-2">
                  <input v-model="number" type="number">
                </div>
              </div>
            </div>

            <hr v-if="form.isPresent === true && form.number !== null">

            <div v-if="form.isPresent === true && form.number !== null">
              <div class="row">
                <div class="col text-center my-2">
                  Merci de répondre au formulaire pour chaque personne (même vous !)
                </div>
              </div>
              <div v-for="(value, index) in form.guests" :key="index">
                <div class="card my-3">
                  <div class="card-body">

                    <h5 class="card-title">
                      Invité n°{{ index + 1 }}
                    </h5>

                    <div class="row" v-if="number && number > 0">
                      <div class="col-12 my-2 contact">
                        <input v-model="form.guests[index].firstName" type="text" placeholder="Prénom" required>
                      </div>
                    </div>
                    <div class="row" v-if="number && number > 0">
                      <div class="col-12 my-2 contact">
                        <input v-model="form.guests[index].lastName" type="text" placeholder="Nom">
                      </div>
                    </div>
                    <div class="row" v-if="number && number > 0">
                      <div class="col-12 my-2 contact">
                        <textarea
                          v-model="form.guests[index].comment"
                          cols="50"
                          rows="3"
                          placeholder="Commentaire (allergies, intolérances, etc.)"/>
                      </div>
                    </div>

                    <div class="row justify-content-around" v-if="number && number > 0">
                      <div class="col my-2" v-if="number && number > 0 && guest.isInvitedApero">
                        <button v-if="form.guests[index].kid === true" class="btn btn-success contact"
                                v-on:click="updateKid(form.guests[index])"><i class="fas fa-child"></i> Enfant
                        </button>
                        <button v-if="form.guests[index].kid === false" class="btn btn-light contact"
                                v-on:click="updateKid(form.guests[index])"><i class="fas fa-child"></i> Enfant
                        </button>
                      </div>
                    </div>
                    <div class="row justify-content-around" v-if="number && number > 0">
                      <div class="col my-2" v-if="number && number > 0 && guest.isInvitedApero">
                        <button v-if="form.guests[index].apero === true" class="btn btn-success contact"
                                v-on:click="updateApero(form.guests[index])"><i class="fas fa-cocktail"></i> Cocktail
                        </button>
                        <button v-if="form.guests[index].apero === false" class="btn btn-light contact"
                                v-on:click="updateApero(form.guests[index])"><i class="fas fa-cocktail"></i> Coktail
                        </button>
                      </div>
                    </div>
                    <div class="row justify-content-around" v-if="number && number > 0">
                      <div class="col my-2 contact" v-if="number && number > 0 && guest.isInvitedApero && guest.isInvitedDinner">
                        <button v-if="form.guests[index].dinner === true" class="btn btn-success contact" v-on:click="updateDinner(form.guests[index])"><i class="fas fa-utensils"></i> Dîner
                        </button>
                        <button v-if="form.guests[index].dinner === false" class="btn btn-light contact"
                                v-on:click="updateDinner(form.guests[index])"><i class="fas fa-utensils"></i> Dîner
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="form.isPresent === true && form.number !== null">
              <div class="row">
                <div class="col text-center my-2">
                  Si vous le voulez, vous pouvez laisser un petit message aux futurs mariés :
                </div>
              </div>
              <div class="row">
                <div class="col text-center my-2">
                  <textarea v-model="form.message" cols="50" rows="10"></textarea>
                </div>
              </div>
            </div>
          </div>
        <div class="alert alert-warning" v-if="validCode && form.isPresent !== null && errors.length > 0">
          Merci d'indiquer au moins un prénom à chaque invité.
        </div>
          <div class="float-right">
            <button class="btn btn-success contact" type="submit" v-if="validCode && form.isPresent !== null" v-on:click="saveGuestInvitation">
              Envoyer les informations
            </button>
          </div>
      </div>

      <div v-if="lost" class="p-lg-5 mx-auto my-2">
        <div v-if="validSendLostCode === null">
          <div class="row">
            <div class="col text-center mb-4">
              Pas de panique ! Si vous avez perdu votre code, il suffit de nous dire qui vous êtes. <br>
              On vous enverra votre code au plus vite <i class="far fa-smile"></i>
            </div>
          </div>
          <div class="row">
            <div class="col text-center my-4">
              <input v-model="nameLostCode" type="text">
            </div>
          </div>
          <div class="text-center" v-if="validCode === null">
            <button class="btn btn-success contact my-3" v-on:click="sendLostCode(nameLostCode)">Valider</button>
          </div>
        </div>
        <div v-if="validSendLostCode">
          <div class="alert alert-info my-3">
            Un message a été envoyé à Manon & Xavier. Si vous êtes bien sur la liste des invités, ils vous enverront
            bientôt le code qui vous permettra de répondre à leur invitation.
          </div>
        </div>
        <div v-if="validSendLostCode === false">
          <div class="alert alert-danger my-3">
            Une erreur s'est produite. Merci d'essayer à nouveau. <br>
            Si le problème persiste, n'hésitez pas à contacter Manon & Xavier via le formulaire de contact.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "FormGuestInvitation",
  data: function () {
    return {
      lost: false,
      nameLostCode: null,
      validSendLostCode: null,
      number: null,
      code: null,
      validCode: null,
      guest: {},
      codeAlreadyExists: null,
      form: {
        code: '',
        isPresent: null,
        number: null,
        message: '',
        guests: []
      },
      errors: []
    }
  },
  methods: {
    updateIsPresent(val) {
      this.form.isPresent = val
    },
    updateKid(guest) {
      guest.kid = !guest.kid
    },
    updateApero(guest) {
      guest.apero = !guest.apero
    },
    updateDinner(guest) {
      guest.dinner = !guest.dinner
    },
    lostCode() {
      this.lost = true;
    },
    sendLostCode(name) {
      this.$http.post('/guest/lost_code', {
        name: this.nameLostCode
      }).then((res) => {
        if (res.data.status === 'ok') {
          this.validSendLostCode = true
        } else {
          this.validSendLostCode = false
        }
      })
    },
    sendCode(code) {
      this.$http.post('/guest/code_invitation', {
        code: this.code
      }).then((res) => {
        if (res.data.status === 'ok') {
          this.validCode = true
          this.guest = res.data.guest
        } else {
          this.validCode = false
        }
      })
    },
    saveGuestInvitation() {
      this.form.guests.forEach((invite) => {
        if (invite.firstName) this.errors = []
        if (!invite.firstName) this.errors.push("Merci d'indiquer des prénoms")
      })
      if (this.errors.length === 0) {
        this.$http.post('/guest/save_invitation', {
          form: this.form,
          guest: this.guest
        }).then((res) => {
          if (res.data.status === 'ok') {
            window.location.replace(res.data.path)
          }
        })
      }
    }
  },
  watch: {
    number: function (val) {
      if (val && val.length > 0) {
        this.form.number = val
        for (let i = 0; i < val; i++) {
          this.form.guests.push({ 'firstName': '', 'lastName': '', 'comment': '', 'apero': false, 'dinner': false, 'kid': false })
        }
      }
      if (val.length === 0) {
        this.form.guests = []
      }
    }
  }
}
</script>
