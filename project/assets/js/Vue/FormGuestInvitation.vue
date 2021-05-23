<template>
  <div id="app">
    <div class="position-relative overflow-hidden text-center">
      <div class="p-lg-5 mx-auto my-2">
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
          <div class="row justify-content-center" v-if="validCode === null">
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
                  Combien serez-vous ?
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
                  Merci d'indiquer les noms et prénoms des personnes qui vous accompagneront
                </div>
              </div>
              <div v-for="(value, index) in form.guests" :key="index">
                <div class="card my-3">
                  <div class="card-body">

                    <div class="card-title">
                      Invité n°{{ index + 1 }}
                    </div>

                    <div class="row justify-content-around" v-if="number && number > 0">
                      <div class="col my-2">
                        <input v-model="form.guests[index].firstName" type="text" placeholder="Prénom" required>
                      </div>
                      <div class="col my-2">
                        <input v-model="form.guests[index].lastName" type="text" placeholder="Nom">
                      </div>
                      <div class="col my-2">
                <textarea
                    v-model="form.guests[index].comment"
                    cols="50"
                    rows="1"
                    placeholder="Commentaire (allergies, intolérances, etc.)"/>
                      </div>
                    </div>
                    <div v-if="number && number > 0 && guest.isInvitedApero">
                      <input type="checkbox" v-model="form.guests[index].apero" :id="index + 1 + 'apero'" name="apero">
                      <label :for="index + 1 + 'apero'">Cocktail</label>
                    </div>
                    <div v-if="number && number > 0 && guest.isInvitedApero && guest.isInvitedDinner">
                      <input type="checkbox" v-model="form.guests[index].dinner" :id="index + 1 + 'dinner'"
                             name="dinner">
                      <label :for="index + 1 + 'dinner'">Dîner</label>
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
    </div>
  </div>
</template>

<script>
export default {
  name: "FormGuestInvitation",
  data: function () {
    return {
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
    sendCode(code) {
      this.$http.post('/guest/code_invitation', {
        code: this.code,
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
          this.form.guests.push({ 'firstName': '', 'lastName': '', 'comment': '', 'apero': false, 'dinner': false })
        }
      }
      if (val.length === 0) {
        this.form.guests = []
      }
    }
  }
}
</script>
