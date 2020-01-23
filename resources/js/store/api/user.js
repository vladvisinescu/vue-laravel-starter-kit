import BaseApiClient from "./base_client";

class UserAPI extends BaseApiClient{
    getUser(userID) {
        this.get()
    }
}

export default UserAPI
