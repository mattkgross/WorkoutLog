<%@ Control Language="C#" AutoEventWireup="true" CodeBehind="uc_CreateTeam.ascx.cs" Inherits="WorkoutLog.Team.uc.uc_CreateTeam" %>

<!-- Disable Autocomplete -->


<!-- Small modal -->
<button type="button" id="NewTeamButton" class="pure-button pure-button-primary-green" title="Create Team" data-toggle="modal" data-target="#createTeamModal">
    <i class="fa fa-plus button-icon" aria-hidden="true"></i><span class="button-text">Team</span>
</button>

<div class="modal fade" id="createTeamModal" tabindex="-1" role="dialog" aria-labelledby="createTeamModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="text-center create-team-modal-content">
                <h4 class="create-team-header">Let's Create A New Team</h4>
                <asp:TextBox ID="TeamName" runat="server" CssClass="form-control align-center text-center" TextMode="SingleLine" AutoCompleteType="Disabled" ReadOnly="true"></asp:TextBox>
                <asp:RequiredFieldValidator ID="TeamNameValidator" ControlToValidate="TeamName" runat="server" ErrorMessage="A team name is required." Visible="false"></asp:RequiredFieldValidator>
                <asp:TextBox ID="EnrollKey" runat="server" CssClass="form-control align-center text-center create-team-input" TextMode="Password" AutoCompleteType="Disabled" ReadOnly="true"></asp:TextBox>
                <asp:Button ID="CreateTeamButton" runat="server" Text="Create Team" CssClass="modal-button pure-button pure-button-primary-green" OnClick="CreateTeamButton_Click" />
            </div>
        </div>
    </div>
</div>