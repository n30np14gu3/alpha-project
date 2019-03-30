<div class="ui stackable grid">
    <div class="eleven wide column">
        <div class="row">
            <div class="bold">Profile Details</div>
            <div class="ui divider"></div>
            <form class="ui form" method="post">
                <div class="two fields">
                    <div class="field">
                        <label>Nickname</label>
                        <input type="text" name="account[nickname]" placeholder="Nickname" required>
                    </div>
                    <div class="field">
                        <label>Birthday</label>
                        <input type="text" name="account[birthday]" placeholder="Birthday" required>
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>First Name</label>
                        <input type="text" name="account[first-name]" placeholder="First Name" required>
                    </div>
                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="account[email]" placeholder="Email" required>
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>Last Name</label>
                        <input type="text" name="account[last-name]" placeholder="Last Name" required>
                    </div>
                    <div class="field">
                        <label>Gender</label>
                        <select class="ui fluid dropdown" required>
                            <option value="">Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label>Steam</label>
                    <div class="fields">
                        <div class="twelve wide field">
                            <input type="text" id="steam-link" placeholder="Account steam URL" required>
                        </div>
                        <div class="four wide field">
                            <input class="ui fluid button alpha" type="button" id="verify-account" value="Verify">
                        </div>
                    </div>
                </div>
                <input class="ui alpha button" tabindex="0" type="submit" value="Save">
            </form>
        </div>
    </div>
    <div class="five wide column">
        <div class="row">
            <div class="bold">Information</div>
            <div class="ui divider"></div>
            <div class="text container">
                Пригласи своего друга и получай 10% с каждого его пополнения!
            </div>
            <div style="margin-bottom: 30px"></div>
            <div class="bold">Your link</div>
            <div class="ui divider"></div>
            <div class="text container">
                https://alpha-cheat.io/invite/code
            </div>
        </div>
    </div>
</div>