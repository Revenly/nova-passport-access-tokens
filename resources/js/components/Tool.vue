<template>
  <div>
    <h1>Access Tokens</h1>

    <div v-show="accessToken.length == 0" class="card mt-3">
      <div class="flex border-b border-40">
        <div class="w-1/5 py-6 px-8">
          <label
            for="access-token-name"
            class="inline-block text-80 pt-2 leading-tight"
          >
            Name
          </label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <input
            id="access-token-name"
            :class="{  'border-danger': nameError }"
            v-model="tokenName"
            type="text"
            placeholder="Name"
            class="w-full form-control form-input form-input-bordered"
          />
          <div
            v-if="nameError"
            class="help-text error-text mt-2 text-danger"
           
          > {{nameError}}</div>
        </div>
      </div>
      <div class="flex border-b border-40">
        <div class="w-1/5 py-6 px-8">
          <label
            for="token-scopes"
            class="inline-block text-80 pt-2 leading-tight"
          >
            User
          </label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <select
            class="w-full form-control form-input form-input-bordered"
            :class="{  'border-danger': userError }"
            v-model="selectedUser"
          >
            <option v-for="option in users" :value="option.id" :key="option.id">
              {{ option.description }}
            </option>
          </select>
          <div class="help-text error-text mt-2 text-danger" v-if="userError">
           {{userError}}
          </div>
        </div>
      </div>
      <div class="flex border-b border-40">
        <div class="w-1/5 py-6 px-8">
          <label
            for="token-scopes"
            class="inline-block text-80 pt-2 leading-tight"
          >
            Scopes
          </label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <multiselect
            id="token-scopes"
            v-model="selectedScopes"
            :options="scopes"
            :multiple="true"
            :hide-selected="true"
            :close-on-select="false"
            :clear-on-select="false"
            :preserve-search="false"
            :internal-search="false"
            :preselect-first="false"
            :class="{ 'border-danger': scopesError }"
            placeholder="Select scopes..."
            label="description"
            track-by="id"
          ></multiselect>
          <div v-if="scopesError" class="help-text error-text mt-2 text-danger">
            {{scopesError}}
          </div>
        </div>
      </div>
      <div class="flex items-center">
        <div class="w-1/5 py-3 px-8"></div>
        <div class="pt-6 pb-6 px-8 w-1/2">
          <button
            :disabled="isBusy"
            type="submit"
            class="btn btn-default btn-primary inline-flex items-center relative"
            @click="storeAccessToken"
          >
            {{ isBusy ? "Please wait..." :"Create Access Token"}}
          </button>
        </div>
      </div>
    </div>
    <div v-show="accessToken.length > 0" class="card mt-3">
      <div class="flex border-b border-40 remove-bottom-border">
        <div class="w-1/5 py-6 px-8">
          <label
            for="accessToken"
            class="inline-block text-80 pt-2 leading-tight"
          >
            New Access Token
          </label>
        </div>
        <div class="py-6 px-8 w-4/5">
          <textarea
            id="accessToken"
            rows="5"
            class="w-full form-control form-input form-input-bordered py-3 h-auto"
            v-text="accessToken"
          ></textarea>
          <div class="help-text help-text mt-2">
            Here is your new access token.
            <strong
              >This is the only time it will be shown so don't lose it!</strong
            >
            You may now use this token to make API requests.
          </div>
        </div>
      </div>
      <div class="flex items-center">
        <div class="w-1/5 pt-0 pb-6 px-8"></div>
        <div class="pt-0 pb-6 px-8 w-1/2">
          <button
            type="submit"
            class="btn btn-default btn-danger inline-flex items-center relative"
            @click="clearAccessToken"
          >
            Close
          </button>
        </div>
      </div>
    </div>

    <div class="card mt-3">
      <p
        v-show="accessTokens.length === 0"
        class="p-6 text-center text-gray-500"
      >
        You have not yet created any Access Tokens.
      </p>
      <div v-show="accessTokens.length > 0" class="relative">
        <div class="overflow-hidden overflow-x-auto relative rounded-lg">
          <table
            cellpadding="0"
            cellspacing="0"
            data-testid="resource-table"
            class="table w-full"
          >
            <thead>
              <tr>
                <th class="text-center">Name</th>
                <th class="text-center">Expires At</th>
                <th class="text-center">Scopes</th>
                <th class="w-16">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <tr
                class="text-center"
                v-for="token in accessTokens"
                :key="token.id"
              >
                <td>
                  <span
                    class="whitespace-no-wrap text-left"
                    via-resource=""
                    via-resource-id=""
                  >
                    {{ token.name }}
                  </span>
                </td>
                <td>
                  <span
                    class="whitespace-no-wrap text-right"
                    via-resource=""
                    via-resource-id=""
                  >
                    {{ token.expires_at | diffForHumans }}
                  </span>
                </td>
                <td>
                  <span
                    class="whitespace-no-wrap text-right"
                    via-resource=""
                    via-resource-id=""
                  >
                    {{ token.scopes.join(", ") }}
                  </span>
                </td>
                <td class="whitespace-no-wrap text-right">
                  <a
                    class="cursor-pointer text-danger"
                    @click="revokeToken(token)"
                  >
                    <svg
                      class="fill-current"
                      aria-hidden="true"
                      focusable="false"
                      role="img"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 448 512"
                      width="20"
                      height="20"
                    >
                      <path
                        d="M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12zM432 80h-82.41l-34-56.7A48 48 0 0 0 274.41 0H173.59a48 48 0 0 0-41.16 23.3L98.41 80H16A16 16 0 0 0 0 96v16a16 16 0 0 0 16 16h16v336a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128h16a16 16 0 0 0 16-16V96a16 16 0 0 0-16-16zM171.84 50.91A6 6 0 0 1 177 48h94a6 6 0 0 1 5.15 2.91L293.61 80H154.39zM368 464H80V128h288zm-212-48h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12z"
                      ></path>
                    </svg>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

export default {
  name: "Tool",
  created() {
    dayjs.extend(relativeTime);
  },
  filters: {
    diffForHumans: (date) => {
      if (!date) {
        return null;
      }
      return dayjs(date).fromNow();
    },
  },
  components: {
    multiselect: Multiselect,
  },
  data: function() {
    return {
      accessToken: "",
      accessTokens: [],
      scopes: [],
      selectedScopes: [],
      tokenErrors: {},
      tokenName: "",
      users: [],
      selectedUser: null,
      userError: null,
      nameError: null,
      scopesError: null,
      isBusy: false
    };
  },
  mounted() {
    this.getAccessTokens();
    this.getScopes();
    this.getUsers();
  },

  methods: {
    clearAccessToken: function() {
      this.accessToken = "";
    },
    tokenFieldHasError: function(field) {
      return ((this.clientErrors || {})[field] || []).join(" ").length > 0;
    },

    tokenFieldError: function(field) {
      return ((this.clientErrors || {})[field] || []).join(" ");
    },

    getUsers: function() {
      axios.get("/nova-vendor/nova-passport-manager/users").then((response) => {
        let mappedUsers = response.data.users.map(function(user) {
          return {
            id: user.email,
            description: `${user.first_name} ${user.last_name}`,
          };
        });
        this.users = Object.assign([], mappedUsers);
      });
    },
    getAccessTokens: function() {
      axios
        .get("/nova-vendor/nova-passport-manager/tokens")
        .then((response) => {
          this.accessTokens = Object.assign([], response.data);
        });
    },

    getScopes() {
      axios.get("/oauth/scopes").then((response) => {
        this.scopes = Object.assign([], response.data);
      });
    },


    storeAccessToken: function() {
      this.accessToken = "";
      this.tokenErrors = Object.assign({});
      this.isBusy = true
      axios
        .post("/nova-vendor/nova-passport-manager/token", {
          name: this.tokenName,
          user: this.selectedUser,
          scopes: this.selectedScopes.map((scope) => {
            return scope.id;
          }),
        })
        .then((response) => {
          this.tokenName = "";
          this.isBusy = false
          this.userError = null
          this.scopesError = null
          this.nameError = null
          this.selectedScopes = Object.assign([]);
          this.tokenErrors = Object.assign({});
          this.accessToken = response.data.token.accessToken;
          this.accessTokens.push(response.data.token.token);
          this.$toasted.show('Access token generated successfully!!', { type: 'success' })
        })
        .catch((error) => {
          this.tokenErrors = ["Something went wrong. Please try again."];
          this.isBusy = false
          if (typeof error.response.data === "object") {
            const errs = error.response.data.errors;
            const errorKeys = Object.getOwnPropertyNames(
              error.response.data.errors
            );

            if (errorKeys.includes("name")) {
              this.nameError = errs["name"].join(',');
            }

            if (errorKeys.includes("scopes")) {
              this.scopesError = errs["scopes"].join(',');
            }

            if (errorKeys.includes("user")) {
              this.userError = errs["user"].join(',');
            }
          }
        });
    },

    revokeToken: function(token) {
      axios
        .delete("/nova-vendor/nova-passport-manager/token/" + token.id)
        .then((response) => {
          this.getAccessTokens();
            this.$toasted.show('Access token revoked successfully!!', { type: 'success' })
        });
    },
  },
};
</script>
>
