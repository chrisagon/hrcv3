var FiltersEnabled = 0; // if your not going to use transitions or filters in any of the tips set this to 0
var spacer="&nbsp; &nbsp; &nbsp; ";

// email notifications to admin
notifyAdminNewMembers0Tip=["", spacer+"No email notifications to admin."];
notifyAdminNewMembers1Tip=["", spacer+"Notify admin only when a new member is waiting for approval."];
notifyAdminNewMembers2Tip=["", spacer+"Notify admin for all new sign-ups."];

// visitorSignup
visitorSignup0Tip=["", spacer+"If this option is selected, visitors will not be able to join this group unless the admin manually moves them to this group from the admin area."];
visitorSignup1Tip=["", spacer+"If this option is selected, visitors can join this group but will not be able to sign in unless the admin approves them from the admin area."];
visitorSignup2Tip=["", spacer+"If this option is selected, visitors can join this group and will be able to sign in instantly with no need for admin approval."];

// curriculum_vitae table
curriculum_vitae_addTip=["",spacer+"This option allows all members of the group to add records to the 'Votre Curriculum vitae' table. A member who adds a record to the table becomes the 'owner' of that record."];

curriculum_vitae_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Votre Curriculum vitae' table."];
curriculum_vitae_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Votre Curriculum vitae' table."];
curriculum_vitae_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Votre Curriculum vitae' table."];
curriculum_vitae_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Votre Curriculum vitae' table."];

curriculum_vitae_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Votre Curriculum vitae' table."];
curriculum_vitae_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Votre Curriculum vitae' table."];
curriculum_vitae_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Votre Curriculum vitae' table."];
curriculum_vitae_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Votre Curriculum vitae' table, regardless of their owner."];

curriculum_vitae_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Votre Curriculum vitae' table."];
curriculum_vitae_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Votre Curriculum vitae' table."];
curriculum_vitae_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Votre Curriculum vitae' table."];
curriculum_vitae_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Votre Curriculum vitae' table."];

// consultant table
consultant_addTip=["",spacer+"This option allows all members of the group to add records to the 'Consultant' table. A member who adds a record to the table becomes the 'owner' of that record."];

consultant_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Consultant' table."];
consultant_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Consultant' table."];
consultant_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Consultant' table."];
consultant_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Consultant' table."];

consultant_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Consultant' table."];
consultant_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Consultant' table."];
consultant_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Consultant' table."];
consultant_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Consultant' table, regardless of their owner."];

consultant_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Consultant' table."];
consultant_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Consultant' table."];
consultant_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Consultant' table."];
consultant_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Consultant' table."];

// missions table
missions_addTip=["",spacer+"This option allows all members of the group to add records to the 'Vos Missions' table. A member who adds a record to the table becomes the 'owner' of that record."];

missions_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Vos Missions' table."];
missions_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Vos Missions' table."];
missions_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Vos Missions' table."];
missions_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Vos Missions' table."];

missions_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Vos Missions' table."];
missions_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Vos Missions' table."];
missions_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Vos Missions' table."];
missions_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Vos Missions' table, regardless of their owner."];

missions_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Vos Missions' table."];
missions_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Vos Missions' table."];
missions_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Vos Missions' table."];
missions_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Vos Missions' table."];

// competences_individuelles table
competences_individuelles_addTip=["",spacer+"This option allows all members of the group to add records to the 'Vos Competences ' table. A member who adds a record to the table becomes the 'owner' of that record."];

competences_individuelles_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Vos Competences ' table."];
competences_individuelles_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Vos Competences ' table."];
competences_individuelles_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Vos Competences ' table."];
competences_individuelles_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Vos Competences ' table."];

competences_individuelles_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Vos Competences ' table."];
competences_individuelles_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Vos Competences ' table."];
competences_individuelles_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Vos Competences ' table."];
competences_individuelles_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Vos Competences ' table, regardless of their owner."];

competences_individuelles_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Vos Competences ' table."];
competences_individuelles_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Vos Competences ' table."];
competences_individuelles_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Vos Competences ' table."];
competences_individuelles_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Vos Competences ' table."];

// client table
client_addTip=["",spacer+"This option allows all members of the group to add records to the 'Client' table. A member who adds a record to the table becomes the 'owner' of that record."];

client_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Client' table."];
client_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Client' table."];
client_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Client' table."];
client_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Client' table."];

client_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Client' table."];
client_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Client' table."];
client_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Client' table."];
client_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Client' table, regardless of their owner."];

client_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Client' table."];
client_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Client' table."];
client_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Client' table."];
client_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Client' table."];

// competences_ref table
competences_ref_addTip=["",spacer+"This option allows all members of the group to add records to the 'R&#233;f&#233;rentiel des comp&#233;tences' table. A member who adds a record to the table becomes the 'owner' of that record."];

competences_ref_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];
competences_ref_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];
competences_ref_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];
competences_ref_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];

competences_ref_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];
competences_ref_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];
competences_ref_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];
competences_ref_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'R&#233;f&#233;rentiel des comp&#233;tences' table, regardless of their owner."];

competences_ref_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];
competences_ref_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];
competences_ref_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];
competences_ref_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'R&#233;f&#233;rentiel des comp&#233;tences' table."];

// domaine table
domaine_addTip=["",spacer+"This option allows all members of the group to add records to the 'Domaine' table. A member who adds a record to the table becomes the 'owner' of that record."];

domaine_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Domaine' table."];
domaine_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Domaine' table."];
domaine_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Domaine' table."];
domaine_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Domaine' table."];

domaine_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Domaine' table."];
domaine_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Domaine' table."];
domaine_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Domaine' table."];
domaine_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Domaine' table, regardless of their owner."];

domaine_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Domaine' table."];
domaine_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Domaine' table."];
domaine_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Domaine' table."];
domaine_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Domaine' table."];

// filiere table
filiere_addTip=["",spacer+"This option allows all members of the group to add records to the 'Filiere' table. A member who adds a record to the table becomes the 'owner' of that record."];

filiere_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Filiere' table."];
filiere_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Filiere' table."];
filiere_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Filiere' table."];
filiere_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Filiere' table."];

filiere_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Filiere' table."];
filiere_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Filiere' table."];
filiere_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Filiere' table."];
filiere_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Filiere' table, regardless of their owner."];

filiere_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Filiere' table."];
filiere_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Filiere' table."];
filiere_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Filiere' table."];
filiere_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Filiere' table."];

// niveaux_ref table
niveaux_ref_addTip=["",spacer+"This option allows all members of the group to add records to the 'Niveaux ref' table. A member who adds a record to the table becomes the 'owner' of that record."];

niveaux_ref_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Niveaux ref' table."];
niveaux_ref_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Niveaux ref' table."];
niveaux_ref_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Niveaux ref' table."];
niveaux_ref_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Niveaux ref' table."];

niveaux_ref_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Niveaux ref' table."];
niveaux_ref_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Niveaux ref' table."];
niveaux_ref_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Niveaux ref' table."];
niveaux_ref_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Niveaux ref' table, regardless of their owner."];

niveaux_ref_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Niveaux ref' table."];
niveaux_ref_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Niveaux ref' table."];
niveaux_ref_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Niveaux ref' table."];
niveaux_ref_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Niveaux ref' table."];

// carriere_consultant table
carriere_consultant_addTip=["",spacer+"This option allows all members of the group to add records to the 'Votre Carriere' table. A member who adds a record to the table becomes the 'owner' of that record."];

carriere_consultant_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Votre Carriere' table."];
carriere_consultant_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Votre Carriere' table."];
carriere_consultant_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Votre Carriere' table."];
carriere_consultant_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Votre Carriere' table."];

carriere_consultant_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Votre Carriere' table."];
carriere_consultant_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Votre Carriere' table."];
carriere_consultant_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Votre Carriere' table."];
carriere_consultant_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Votre Carriere' table, regardless of their owner."];

carriere_consultant_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Votre Carriere' table."];
carriere_consultant_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Votre Carriere' table."];
carriere_consultant_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Votre Carriere' table."];
carriere_consultant_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Votre Carriere' table."];

// formation_suivi table
formation_suivi_addTip=["",spacer+"This option allows all members of the group to add records to the 'Formations suivis' table. A member who adds a record to the table becomes the 'owner' of that record."];

formation_suivi_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Formations suivis' table."];
formation_suivi_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Formations suivis' table."];
formation_suivi_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Formations suivis' table."];
formation_suivi_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Formations suivis' table."];

formation_suivi_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Formations suivis' table."];
formation_suivi_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Formations suivis' table."];
formation_suivi_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Formations suivis' table."];
formation_suivi_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Formations suivis' table, regardless of their owner."];

formation_suivi_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Formations suivis' table."];
formation_suivi_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Formations suivis' table."];
formation_suivi_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Formations suivis' table."];
formation_suivi_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Formations suivis' table."];

// feedback table
feedback_addTip=["",spacer+"This option allows all members of the group to add records to the 'Feedback' table. A member who adds a record to the table becomes the 'owner' of that record."];

feedback_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Feedback' table."];
feedback_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Feedback' table."];
feedback_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Feedback' table."];
feedback_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Feedback' table."];

feedback_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Feedback' table."];
feedback_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Feedback' table."];
feedback_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Feedback' table."];
feedback_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Feedback' table, regardless of their owner."];

feedback_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Feedback' table."];
feedback_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Feedback' table."];
feedback_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Feedback' table."];
feedback_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Feedback' table."];

// emploi_fonctionnel table
emploi_fonctionnel_addTip=["",spacer+"This option allows all members of the group to add records to the 'Emploi fonctionnel' table. A member who adds a record to the table becomes the 'owner' of that record."];

emploi_fonctionnel_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Emploi fonctionnel' table."];
emploi_fonctionnel_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Emploi fonctionnel' table."];
emploi_fonctionnel_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Emploi fonctionnel' table."];
emploi_fonctionnel_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Emploi fonctionnel' table."];

emploi_fonctionnel_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Emploi fonctionnel' table."];
emploi_fonctionnel_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Emploi fonctionnel' table."];
emploi_fonctionnel_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Emploi fonctionnel' table."];
emploi_fonctionnel_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Emploi fonctionnel' table, regardless of their owner."];

emploi_fonctionnel_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Emploi fonctionnel' table."];
emploi_fonctionnel_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Emploi fonctionnel' table."];
emploi_fonctionnel_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Emploi fonctionnel' table."];
emploi_fonctionnel_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Emploi fonctionnel' table."];

/*
	Style syntax:
	-------------
	[TitleColor,TextColor,TitleBgColor,TextBgColor,TitleBgImag,TextBgImag,TitleTextAlign,
	TextTextAlign,TitleFontFace,TextFontFace, TipPosition, StickyStyle, TitleFontSize,
	TextFontSize, Width, Height, BorderSize, PadTextArea, CoordinateX , CoordinateY,
	TransitionNumber, TransitionDuration, TransparencyLevel ,ShadowType, ShadowColor]

*/

toolTipStyle=["white","#00008B","#000099","#E6E6FA","","images/helpBg.gif","","","","\"Trebuchet MS\", sans-serif","","","","3",400,"",1,2,10,10,51,1,0,"",""];

applyCssFilter();
