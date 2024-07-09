<?php
$translations = [
    'en' => [
        // Geral
        'general_go_back' => 'Go Back',
        'error_occurred' => 'An error occurred. Please try again later.',

        // Home Page
        'home_title' => 'Share Your Phrases',
        'home_sub_title' => 'With Anyone',
        'home_description' => 'Your phrase is encrypted in your browser before being stored for a limited period of time and read operations. Unencrypted data never leaves your browser.',
        'home_login_button' => 'Login',
        'home_register_button' => 'Register',

        // Login Page
        'login_page_title' => 'Login',

        'login_welcome' => 'Welcome back!',
        'login_information' => 'By signing in, you agree to our',
        'login_terms' => 'terms',
        'login_privacy' => 'privacy policy',

        'login_submit' => 'Log In',
        'login_forgot_password' => 'Forgot your password?',

        'login_invalid_token' => 'Invalid token. Please check your confirmation link.',
        'login_db_error' => 'An error occurred. Please try again later.',
        'login_token_not_provided' => 'Token not provided.',
        'login_unknown_error' => 'Unknown error.',
        'login_email_required' => 'The email is required.',
        'login_password_required' => 'The Password is required.',
        'login_account_not_verified' => 'Your account has not been verified yet. Please check your email.',
        'login_invalid_email_password' => 'Invalid email or password.',

        'login_success_message' => 'You have successfully confirmed your account.',

        'login_no_account' => "Don't have an account?",
        'login_register' => 'Sign Up',

        // Register Page
        'register_page_title' => 'Register',

        'register_welcome' => 'First time here?',
        'register_information' => 'By signing up, you agree to our',
        'register_terms' => 'terms',
        'register_privacy' => 'privacy policy',

        'register_submit' => 'Sign Up',

        'register_username_required' => 'Username is required.',
        'register_email_required' => 'Email is required.',
        'register_invalid_email_format' => 'Invalid email format.',
        'register_password_required' => 'Password is required.',
        'register_password_length' => 'Password must be at least 8 characters long.',
        'register_password_uppercase' => 'Password must include at least one uppercase letter.',
        'register_password_lowercase' => 'Password must include at least one lowercase letter.',
        'register_password_number' => 'Password must include at least one number.',
        'register_password_special' => 'Password must include at least one special character.',
        'register_email_exists' => 'An account with this email already exists.',
        'register_email_error' => 'An error occurred while sending the email. Please try again later.',
        'register_email_subject' => 'Confirm Your PhraseShare Registration',
        'register_email_body' => 'Please confirm your account by clicking the link: ',

        'register_confirmation_sent_success' => 'Email sent successfully. Check your inbox.',

        'register_no_account' => 'Already have an account?',
        'register_login' => 'Log In',

        // Forgot Password Page
        'forgot_page_title' => 'Forgot Password',

        'forgot_welcome' => 'It\'s okay, it can happen!',
        'forgot_information' => 'Enter your email and we\'ll send you a reset link.',

        'forgot_success_message' => 'Check your email for the reset link.',
        'forgot_submit' => 'Send Link',
        'forgot_loading_text' => 'Sending...',

        'forgot_remember_action' => 'Remember your password?',

        'forgot_invalid_email' => 'The email is invalid.',
        'forgot_email_error' => 'An error occurred while sending the email. Please try again later.',
        'forgot_email_not_found' => 'The email address is not registered.',

        // Reset Password Page
        'reset_password_page_title' => 'Reset',

        'reset_password_welcome' => 'Choose your new password.',
        'reset_password_information' => 'A strong password is important to protect you.',

        'reset_password_confirm_label' => 'Confirm',
        'reset_password_submit' => 'Reset Password',

        'reset_password_action' => 'Don\'t want to reset it anymore?',

        'reset_password_success_message' => 'Your password has been reset successfully.',

        // Dashboard Page
        'dashboard_page_title' => 'Dashboard',
        'dashboard_add_phrase' => 'Add Phrase',

        'dashboard_you_have_not_created_phrases' => 'You haven\'t created any phrases.',
        'dashboard_create_phrase_explanation' => 'Once you create a phrase, you\'ll be able to share it with everyone.',

        'dashboard_phrase_title' => 'Title',
        'dashboard_phrase_content' => 'Content',
        'dashboard_phrase_creation_date' => 'Creation Date',
        'dashboard_phrase_status' => 'Status',
        'dashboard_phrase_visibility' => 'Visibility',

        'dashboard_edit_phrase' => 'Edit Phrase',
        'dashboard_show_phrase' => 'Show Phrase',
        'dashboard_hide_phrase' => 'Hide Phrase',
        'dashboard_share_phrase' => 'Share Phrase',
        'dashboard_delete_phrase' => 'Delete Phrase',

        'dashboard_delete_confirmation' => 'Are you sure you want to delete this phrase?',

        // Profile Page
        'profile_page_title' => 'Profile',
        'profile_your_information' => 'Your Information',

        'profile_name' => 'Name',
        'profile_email_address' => 'Email Address',
        'profile_language' => 'Language',

        'profile_save_changes' => 'Save Changes',
        'profile_delete' => 'Delete Account',

        'profile_delete_modal_title' => 'Delete Account?',
        'profile_delete_modal_description' => 'Are you sure you want to delete your account?',
        'profile_delete_modal_type' => 'Type ',
        'profile_delete_modal_type_confirm' => ' to confirm.',
        'profile_delete_modal_warning' => 'This action cannot be undone.',
        'profile_delete_modal_confirm' => 'Delete Account',
        'profile_delete_modal_cancel' => 'Cancel',

        'profile_security_note' => 'For security reasons, we do not allow changing your email.',

        // Create Page
        'create_page_title' => 'Create Phrase',

        'create_write_ai' => 'Write with Artificial Intelligence',
        'create_write_generating' => 'Generating...',

        'create_title_label' => 'Phrase Title:',
        'create_content_label' => 'Phrase Content:',
        'create_visibility_label' => 'Visibility:',
        'create_visibility_option_auto' => 'Show Automatically',
        'create_visibility_option_manual' => 'Decide Manually',
        'create_show_time_label' => 'What time should the phrase be shown?',

        'create_create_button' => 'Create',
        'create_cancel_button' => 'Cancel',

        'create_content_length_error' => 'Phrase content must not exceed 56 characters.',
        'create_show_time_past_error' => 'The show time must be in the future.',

        // Edit Page
        'edit_page_title' => 'Edit Phrase',

        'edit_title_label' => 'Phrase Title:',
        'edit_content_label' => 'Phrase Content:',

        'edit_save_button' => 'Save',
        'edit_cancel_button' => 'Cancel',

        'edit_error_occurred' => 'An error occurred while updating the phrase. Please try again later.',

        // Share Page
        'share_page_title' => 'Share Phrase',

        'share_use_url_label' => 'Use the following URL to share the phrase:',
        'share_visibility_note' => 'You need to make the visibility public for people to see your phrase.',

        'share_loading_text' => 'Loading...',
        'share_see_phrase_button' => 'See Your Phrase',

        // View Page
        'view_page_title' => 'View Phrase',

        'view_created_label' => 'Created',
        'view_visibility_label' => 'Visibility',
        'view_visibility_type_label' => 'Visibility type',
        'view_written_by_label' => 'Written by',

        'view_status_private' => 'Private',
        'view_status_public' => 'Public',
        'view_status_waiting' => 'Waiting publishing',

        'view_phrase_not_published' => "The writer didn't publish the phrase yet.",
        'view_phrase_not_yet_published' => "The phrase is not yet published. Time remaining: ",

        // Admin Dashboard Page
        'admin_dashboard_page_title' => 'Admin Dashboard',

        'admin_dashboard_users_title' => 'Users Overview',
        'admin_dashboard_users_explanation' => 'Here you can see all users activity.',

        'admin_dashboard_phrases_title' => 'Phrases Overview',
        'admin_dashboard_phrases_explanation' => 'Here you can see all phrases activity.',

        'admin_dashboard_click' => 'Click ',
        'admin_dashboard_here' => 'here',
        'admin_dashboard_to_manage_users' => 'to manage all users.',
        'admin_dashboard_to_manage_phrases' => 'to manage all phrases.',

        'admin_dashboard_filter_apply' => 'Apply',

        'admin_dashboard_users_no_users_found' => 'No users found.',
        'admin_dashboard_users_no_users_explanation' => 'You have no users created yet.',

        'admin_dashboard_users_information' => 'You are seeing the last 7 days users registration activity.',
        'admin_dashboard_phrases_information' => 'You are seeing the last 7 days phrases creation activity.',

        // Admin Users Page
        'admin_page_title' => 'Admin',
        'admin_users_page_title' => 'Manage Users',

        'admin_users_no_users_found' => 'No users found.',
        'admin_users_no_users_explanation' => 'You have no users created yet.',

        'admin_users_user_id' => 'ID',
        'admin_users_user_name' => 'Name',
        'admin_users_user_email' => 'Email',
        'admin_users_user_email_confirmed' => 'Email Confirmed',
        'admin_users_user_signup_time' => 'Signup Time',
        'admin_users_user_status' => 'Status',

        'admin_users_view_user' => 'View User',
        'admin_users_edit_user' => 'Edit User',
        'admin_users_block_user' => 'Block User',
        'admin_users_unblock_user' => 'Unblock User',
        'admin_users_delete_user' => 'Delete User',
        'admin_users_delete_user_confirmation' => 'Are you sure you want to delete this user?',

        // Admin Users View Page
        'admin_user_view_page_title' => 'View User',

        'admin_user_view_id_label' => 'ID',
        'admin_user_view_email_confirmed_label' => 'Email Confirmed',
        'admin_user_view_confirmed' => 'True',
        'admin_user_view_not_confirmed' => 'False',
        'admin_user_view_signup_time_label' => 'Signup Time',
        'admin_user_view_status_label' => 'Status',
        'admin_user_view_status_active' => 'Active',
        'admin_user_view_status_blocked' => 'Blocked',
        'admin_user_view_phrases_creaeted_label' => 'Phrases Created',

        // Admin Users Edit Page
        'admin_user_edit_page_title' => 'Edit User',

        'admin_user_edit_username_label' => 'Username',
        'admin_user_edit_email_label' => 'Email',
        'admin_user_edit_status_label' => 'Status',
        'admin_user_edit_status_active' => 'Active',
        'admin_user_edit_status_blocked' => 'Blocked',

        'admin_user_edit_block_user' => 'Block User',
        'admin_user_edit_unblock_user' => 'Unblock User',
        'admin_user_edit_delete_user' => 'Delete User',
        'admin_user_edit_delete_user_confirmation' => 'Are you sure you want to delete this user?',

        // Admin Phrases Page
        'admin_phrases_page_title' => 'Manage Phrases',

        'admin_phrases_no_phrases_found' => 'No phrases found.',
        'admin_phrases_no_phrases_explanation' => 'Users have not created any phrases yet.',

        'admin_phrases_phrase_id' => 'ID',
        'admin_phrases_phrase_title' => 'Title',
        'admin_phrases_phrase_content' => 'Content',
        'admin_phrases_phrase_status' => 'Status',
        'admin_phrases_phrase_visibility' => 'Visibility',
        'admin_phrases_phrase_visibility_public' => 'Public',
        'admin_phrases_phrase_visibility_private' => 'Private',
        'admin_phrases_phrase_visibility_waiting' => 'Waiting publishing',
        'admin_phrases_phrase_creation_date' => 'Creation Date',

        'admin_phrases_view_phrase' => 'View Phrase',
        'admin_phrases_edit_phrase' => 'Edit Phrase',
        'admin_phrases_show_phrase' => 'Show Phrase',
        'admin_phrases_hide_phrase' => 'Hide Phrase',
        'admin_phrases_delete_phrase' => 'Delete Phrase',
        'admin_phrases_delete_phrase_confirmation' => 'Are you sure you want to delete this phrase?',
    ],
    'it' => [
        // Geral
        'general_go_back' => 'Torna indietro',
        'error_occurred' => 'Si è verificato un errore. Riprova più tardi.',

        // Home Page
        'home_title' => 'Condividi le Tue',
        'home_sub_title' => 'Frasi con Chiunque',
        'home_description' => 'La tua frase è crittografata nel tuo browser prima di essere memorizzata per un periodo limitato e per operazioni di lettura. I dati non crittografati non lasciano mai il tuo browser.',
        'home_login_button' => 'Accedi',
        'home_register_button' => 'Registrati',

        // Login Page
        'login_page_title' => 'Accedi',

        'login_welcome' => 'Bentornato!',
        'login_information' => 'Accedendo, accetti i nostri',
        'login_terms' => 'termini',
        'login_privacy' => 'politica sulla privacy',

        'login_submit' => 'Accedi',
        'login_forgot_password' => 'Hai dimenticato la password?',

        'login_invalid_token' => 'Token non valido. Si prega di controllare il link di conferma.',
        'login_db_error' => 'Si è verificato un errore. Per favore riprova più tardi.',
        'login_token_not_provided' => 'Token non fornito.',
        'login_unknown_error' => 'Errore sconosciuto.',
        'login_email_required' => 'L\'email è richiesta.',
        'login_password_required' => 'La password è richiesta.',
        'login_account_not_verified' => 'Il tuo account non è stato ancora verificato. Controlla la tua email.',
        'login_invalid_email_password' => 'Email o password non validi.',

        'login_success_message' => 'Hai confermato con successo il tuo account.',

        'login_no_account' => 'Non hai un account?',
        'login_register' => 'Registrati',

        // Register Page
        'register_page_title' => 'Registrazione',

        'register_welcome' => 'Prima volta qui?',
        'register_information' => 'Registrandoti, accetti i nostri',
        'register_terms' => 'termini',
        'register_privacy' => 'politica sulla privacy',

        'register_submit' => 'Registrati',

        'register_username_required' => 'Il nome utente è obbligatorio.',
        'register_email_required' => 'L\'email è obbligatoria.',
        'register_invalid_email_format' => 'Formato email non valido.',
        'register_password_required' => 'La password è obbligatoria.',
        'register_password_length' => 'La password deve essere lunga almeno 8 caratteri.',
        'register_password_uppercase' => 'La password deve includere almeno una lettera maiuscola.',
        'register_password_lowercase' => 'La password deve includere almeno una lettera minuscola.',
        'register_password_number' => 'La password deve includere almeno un numero.',
        'register_password_special' => 'La password deve includere almeno un carattere speciale.',
        'register_email_exists' => 'Esiste già un account con questa email.',
        'register_email_error' => 'Si è verificato un errore durante l\'invio dell\'email. Riprova più tardi.',
        'register_email_subject' => 'Conferma la tua registrazione su PhraseShare',
        'register_email_body' => 'Per favore, conferma il tuo account cliccando sul link: ',

        'register_confirmation_sent_success' => 'Email inviata con successo. Controlla la tua casella di posta.',

        'register_no_account' => 'Hai già un account?',
        'register_login' => 'Accedi',

        // Forgot Password Page
        'forgot_page_title' => 'Password dimenticata',

        'forgot_welcome' => 'Va bene, può succedere!',
        'forgot_information' => 'Inserisci il tuo indirizzo email e ti invieremo un link di ripristino.',

        'forgot_success_message' => 'Controlla la tua email per il link di ripristino.',
        'forgot_submit' => 'Invia Link',
        'forgot_loading_text' => 'Invio...',

        'forgot_remember_action' => 'Ricordi la tua password?',

        'forgot_invalid_email' => 'L\'email non è valida.',
        'forgot_email_error' => 'Si è verificato un errore durante l\'invio dell\'email. Per favore, riprova più tardi.',
        'forgot_email_not_found' => 'L\'indirizzo email non è registrato.',

        // Reset Password Page
        'reset_password_page_title' => 'Reset',

        'reset_password_welcome' => 'Scegli la tua nuova password.',
        'reset_password_information' => 'Una password forte è importante per proteggerti.',

        'reset_password_confirm_label' => 'Conferma',
        'reset_password_submit' => 'Resetta Password',

        'reset_password_action' => 'Non vuoi più resettarla?',

        'reset_password_success_message' => 'La tua password è stata resettata con successo.',

        // Dashboard Page
        'dashboard_page_title' => 'Dashboard',
        'dashboard_add_phrase' => 'Aggiungi Frase',

        'dashboard_you_have_not_created_phrases' => 'Non hai creato ancora frasi.',
        'dashboard_create_phrase_explanation' => 'Una volta creata una frase, sarai in grado di condividerla con tutti.',

        'dashboard_phrase_title' => 'Titolo',
        'dashboard_phrase_content' => 'Contenuto',
        'dashboard_phrase_creation_date' => 'Data di Creazione',
        'dashboard_phrase_status' => 'Stato',
        'dashboard_phrase_visibility' => 'Visibilità',

        'dashboard_edit_phrase' => 'Modifica Frase',
        'dashboard_show_phrase' => 'Mostra Frase',
        'dashboard_hide_phrase' => 'Nascondi Frase',
        'dashboard_share_phrase' => 'Condividi Frase',
        'dashboard_delete_phrase' => 'Elimina Frase',

        'dashboard_delete_confirmation' => 'Sei sicuro di voler eliminare questa frase?',

        // Profile Page
        'profile_page_title' => 'Profilo',
        'profile_your_information' => 'Le tue informazioni',

        'profile_name' => 'Nome',
        'profile_email_address' => 'Indirizzo email',
        'profile_language' => 'Lingua',

        'profile_save_changes' => 'Salva le modifiche',
        'profile_delete' => 'Elimina Account',

        'profile_delete_modal_title' => 'Elimina Account?',
        'profile_delete_modal_description' => 'Sei sicuro di voler eliminare il tuo account?',
        'profile_delete_modal_type' => 'Digita ',
        'profile_delete_modal_type_confirm' => ' per confermare.',
        'profile_delete_modal_warning' => 'Azione non può essere annullata.',
        'profile_delete_modal_confirm' => 'Elimina Account',
        'profile_delete_modal_cancel' => 'Annulla',

        'profile_security_note' => 'Per motivi di sicurezza, non consentiamo la modifica della tua email.',

        // Create Page
        'create_page_title' => 'Crea Frase',

        'create_write_ai' => 'Scrivi con Intelligenza Artificiale',
        'create_write_generating' => 'Generazione...',

        'create_title_label' => 'Titolo della Frase:',
        'create_content_label' => 'Contenuto della Frase:',
        'create_visibility_label' => 'Visibilità:',
        'create_visibility_option_auto' => 'Mostra Automaticamente',
        'create_visibility_option_manual' => 'Decidi Manualmente',
        'create_show_time_label' => 'A che ora dovrebbe essere mostrata la frase?',

        'create_create_button' => 'Crea',
        'create_cancel_button' => 'Annulla',

        'create_content_length_error' => 'Il contenuto della frase non deve superare i 56 caratteri.',
        'create_show_time_past_error' => 'La data di visualizzazione deve essere nel futuro.',

        // Edit Page
        'edit_page_title' => 'Modifica Frase',

        'edit_title_label' => 'Titolo della Frase:',
        'edit_content_label' => 'Contenuto della Frase:',

        'edit_save_button' => 'Salva',
        'edit_cancel_button' => 'Annulla',

        'edit_error_occurred' => 'Si è verificato un errore durante l\'aggiornamento della frase. Riprova più tardi.',

        // Share Page
        'share_page_title' => 'Condividi Frase',

        'share_use_url_label' => 'Utilizza il seguente URL per condividere la frase:',
        'share_visibility_note' => 'Devi rendere la visibilità pubblica affinché le persone possano vedere la tua frase.',

        'share_loading_text' => 'Caricamento...',
        'share_see_phrase_button' => 'Vedi la tua frase',

        // View Page
        'view_page_title' => 'Visualizza frase',

        'view_created_label' => 'Creato',
        'view_visibility_label' => 'Visibilità',
        'view_visibility_type_label' => 'Tipo di Visibilità',
        'view_written_by_label' => 'Scritto da',

        'view_status_private' => 'Privato',
        'view_status_public' => 'Pubblico',
        'view_status_waiting' => 'In attesa di pubblicazione',

        'view_phrase_not_published' => "L'autore non ha ancora pubblicato la frase.",
        'view_phrase_not_yet_published' => "La frase non è ancora pubblicata. Tempo rimanente: ",

        // Admin Dashboard Page
        'admin_dashboard_page_title' => 'Dashboard di Admin',

        'admin_dashboard_users_title' => 'Panoramica degli Utenti',
        'admin_dashboard_users_explanation' => 'Qui puoi vedere le attività degli utenti.',

        'admin_dashboard_phrases_title' => 'Panoramica delle Frase',
        'admin_dashboard_phrases_explanation' => 'Qui puoi vedere le attività delle frasi.',

        'admin_dashboard_click' => 'Clicca ',
        'admin_dashboard_here' => 'qui',
        'admin_dashboard_to_manage_users' => 'per gestire tutti gli utenti.',
        'admin_dashboard_to_manage_phrases' => 'per gestire tutte le frasi.',

        'admin_dashboard_filter_apply' => 'Applica',

        'admin_dashboard_users_no_users_found' => 'Nessun utente trovato.',
        'admin_dashboard_users_no_users_explanation' => 'Non hai ancora utenti creati.',

        'admin_dashboard_users_information' => 'Stai guardando l\'attività degli utenti nelle ultime 7 giorni.',
        'admin_dashboard_phrases_information' => 'Stai guardando l\'attività delle frasi nelle ultime 7 giorni.',

        // Admin Users Page
        'admin_page_title' => 'Admin',
        'admin_users_page_title' => 'Gestisci Utenti',

        'admin_users_no_users_found' => 'Nessun utente trovato.',
        'admin_users_no_users_explanation' => 'Non hai ancora utenti creati.',

        'admin_users_user_id' => 'ID',
        'admin_users_user_name' => 'Nome',
        'admin_users_user_email' => 'Email',
        'admin_users_user_email_confirmed' => 'Email Confermato',
        'admin_users_user_signup_time' => 'Data Registrazione',
        'admin_users_user_status' => 'Stato',

        'admin_users_view_user' => 'Visualizza Utente',
        'admin_users_edit_user' => 'Modifica Utente',
        'admin_users_block_user' => 'Blocca Utente',
        'admin_users_unblock_user' => 'Sblocca Utente',
        'admin_users_delete_user' => 'Elimina Utente',
        'admin_users_delete_user_confirmation' => 'Sei sicuro di voler eliminare questo utente?',

        // Admin Users View Page
        'admin_user_view_page_title' => 'Visualizza Utente',

        'admin_user_view_id_label' => 'ID',
        'admin_user_view_email_confirmed_label' => 'Email Confermato',
        'admin_user_view_confirmed' => 'Vero',
        'admin_user_view_not_confirmed' => 'Falso',
        'admin_user_view_signup_time_label' => 'Data Registrazione',
        'admin_user_view_status_label' => 'Stato',
        'admin_user_view_status_active' => 'Attivo',
        'admin_user_view_status_blocked' => 'Bloccato',
        'admin_user_view_phrases_creaeted_label' => 'Frase Creata',

        // Admin Users Edit Page
        'admin_user_edit_page_title' => 'Modifica Utente',

        'admin_user_edit_username_label' => 'Nome utente',
        'admin_user_edit_email_label' => 'Email',
        'admin_user_edit_status_label' => 'Stato',
        'admin_user_edit_status_active' => 'Attivo',
        'admin_user_edit_status_blocked' => 'Bloccato',

        'admin_user_edit_block_user' => 'Blocca Utente',
        'admin_user_edit_unblock_user' => 'Sblocca Utente',
        'admin_user_edit_delete_user' => 'Elimina Utente',
        'admin_user_edit_delete_user_confirmation' => 'Sei sicuro di voler eliminare questo utente?',

        // Admin Phrases Page
        'admin_phrases_page_title' => 'Gestisci Frasi',

        'admin_phrases_no_phrases_found' => 'Nessuna frase trovata.',
        'admin_phrases_no_phrases_explanation' => 'Non hai ancora frasi.',

        'admin_phrases_phrase_id' => 'ID',
        'admin_phrases_phrase_title' => 'Titolo',
        'admin_phrases_phrase_content' => 'Contenuto',
        'admin_phrases_phrase_status' => 'Stato',
        'admin_phrases_phrase_visibility' => 'Visibilità',
        'admin_phrases_phrase_visibility_private' => 'Privata',
        'admin_phrases_phrase_visibility_public' => 'Pubblica',
        'admin_phrases_phrase_visibility_waiting' => 'In attesa di pubblicazione',
        'admin_phrases_phrase_creation_date' => 'Data Creazione',

        'admin_phrases_view_phrase' => 'Visualizza Frase',
        'admin_phrases_edit_phrase' => 'Modifica Frase',
        'admin_phrases_show_phrase' => 'Mostra Frase',
        'admin_phrases_hide_phrase' => 'Nascondi Frase',
        'admin_phrases_delete_phrase' => 'Elimina Frase',
        'admin_phrases_delete_phrase_confirmation' => 'Sei sicuro di voler eliminare questa frase?',
    ],
    'pt' => [
        // Geral
        'general_go_back' => 'Voltar',
        'error_occurred' => 'Ocorreu um erro. Por favor, tente novamente mais tarde.',

        // Home Page
        'home_title' => 'Compartilhe Frases',
        'home_sub_title' => 'com Quem Quiser',
        'home_description' => 'Sua frase é criptografada em seu navegador antes de ser armazenada por um período limitado e para operações de leitura. Os dados não criptografados nunca deixam seu navegador.',
        'home_login_button' => 'Entrar',
        'home_register_button' => 'Registrar',

        // Login Page
        'login_page_title' => 'Entrar',

        'login_welcome' => 'Bem-vindo de volta!',
        'login_information' => 'Ao fazer login, concorda com os nossos',
        'login_terms' => 'termos',
        'login_privacy' => 'política de privacidade',

        'login_submit' => 'Entrar',
        'login_forgot_password' => 'Esqueceu-se da password?',

        'login_invalid_token' => 'Token inválido. Por favor, verifique o link de confirmação.',
        'login_db_error' => 'Ocorreu um erro. Por favor, tente novamente mais tarde.',
        'login_token_not_provided' => 'Token não fornecido.',
        'login_unknown_error' => 'Erro desconhecido.',
        'login_email_required' => 'O email é obrigatório.',
        'login_password_required' => 'A password é obrigatória.',
        'login_account_not_verified' => 'Sua conta ainda não foi verificada. Por favor, verifique seu email.',
        'login_invalid_email_password' => 'Email ou password inválidos.',

        'login_success_message' => 'Você confirmou com sucesso sua conta.',

        'login_no_account' => 'Não tem uma conta?',
        'login_register' => 'Registro',

        // Register Page
        'register_page_title' => 'Registrar',

        'register_welcome' => 'Primeira vez aqui?',
        'register_information' => 'Ao se registrar, concorda com os nossos',
        'register_terms' => 'termos',
        'register_privacy' => 'política de privacidade',

        'register_submit' => 'Registrar',

        'register_username_required' => 'O nome de utilizador é obrigatório.',
        'register_email_required' => 'O email é obrigatório.',
        'register_invalid_email_format' => 'O formato de email é inválido.',
        'register_password_required' => 'A password é obrigatória.',
        'register_password_length' => 'A password deve ter pelo menos 8 caracteres.',
        'register_password_uppercase' => 'A password deve incluir pelo menos uma letra maiúscula.',
        'register_password_lowercase' => 'A password deve incluir pelo menos uma letra minúscula.',
        'register_password_number' => 'A password deve incluir pelo menos um número.',
        'register_password_special' => 'A password deve incluir pelo menos um caractere especial.',
        'register_email_exists' => 'Já existe uma conta com este email.',
        'register_email_error' => 'Ocorreu um erro ao enviar o email. Por favor, tente novamente mais tarde.',
        'register_email_subject' => 'Confirme o seu email no PhraseShare',
        'register_email_body' => 'Confirme a sua conta clicando no link: ',

        'register_no_account' => 'Já tem uma conta?',
        'register_login' => 'Entrar',

        'register_confirmation_sent_success' => 'Email enviado com sucesso. Verifique sua caixa de entrada.',

        // Forgot Password Page
        'forgot_page_title' => 'Esqueceu-se da password',

        'forgot_welcome' => 'Tudo bem, pode acontecer!',
        'forgot_information' => 'Digite seu email e enviaremos um link de redefinição.',

        'forgot_success_message' => 'Verifique seu email para o link de redefinição.',
        'forgot_submit' => 'Enviar Link',
        'forgot_loading_text' => 'Enviando...',

        'forgot_remember_action' => 'Lembra-se da password?',

        'forgot_invalid_email' => 'O email é inválido.',
        'forgot_email_error' => 'Ocorreu um erro ao enviar o email. Por favor, tente novamente mais tarde.',
        'forgot_email_not_found' => 'O endereço de email não está registrado.',

        // Reset Password Page
        'reset_password_page_title' => 'Redefinir',

        'reset_password_welcome' => 'Escolha a sua nova password.',
        'reset_password_information' => 'Uma password forte é importante para o proteger.',

        'reset_password_confirm_label' => 'Confirmar',
        'reset_password_submit' => 'Redefinir Password',

        'reset_password_action' => 'Não quer mais redefinir?',

        'reset_password_success_message' => 'A sua password foi redefinida com sucesso.',

        // Dashboard Page
        'dashboard_page_title' => 'Dashboard',
        'dashboard_add_phrase' => 'Adicionar Frase',

        'dashboard_you_have_not_created_phrases' => 'Ainda não criou frases.',
        'dashboard_create_phrase_explanation' => 'Depois de crar uma frase, poderá partilhá-la com quem quiser.',

        'dashboard_phrase_title' => 'Título',
        'dashboard_phrase_content' => 'Conteúdo',
        'dashboard_phrase_creation_date' => 'Data de Criação',
        'dashboard_phrase_status' => 'Estado',
        'dashboard_phrase_visibility' => 'Visibilidade',

        'dashboard_edit_phrase' => 'Editar Frase',
        'dashboard_show_phrase' => 'Mostrar Frase',
        'dashboard_hide_phrase' => 'Esconder Frase',
        'dashboard_share_phrase' => 'Partilhar Frase',
        'dashboard_delete_phrase' => 'Eliminar Frase',

        'dashboard_delete_confirmation' => 'Tem a certeza de que queres eliminar esta frase?',

        // Profile Page
        'profile_page_title' => 'Perfil',
        'profile_your_information' => 'As suas Informações',

        'profile_name' => 'Nome',
        'profile_email_address' => 'Endereço de email',
        'profile_language' => 'Idioma',

        'profile_save_changes' => 'Guardar Alterações',
        'profile_delete' => 'Eliminar Conta',

        'profile_delete_modal_title' => 'Eliminar Conta?',
        'profile_delete_modal_description' => 'Tem a certeza de que quer eliminar a sua conta?',
        'profile_delete_modal_type' => 'Escreva ',
        'profile_delete_modal_type_confirm' => ' para confirmar.',
        'profile_delete_modal_warning' => 'Esta ação não pode ser desfeita.',
        'profile_delete_modal_confirm' => 'Eliminar Conta',
        'profile_delete_modal_cancel' => 'Cancelar',

        'profile_security_note' => 'Por motivos de segurança, não permitimos a alteração do seu email.',

        // Create Page
        'create_page_title' => 'Criar Frase',

        'create_write_ai' => 'Escrever com Inteligência Artificial',
        'create_write_generating' => 'A gerar frase...',

        'create_title_label' => 'Título da Frase:',
        'create_content_label' => 'Conteúdo da Frase:',
        'create_visibility_label' => 'Visibilidade:',
        'create_visibility_option_auto' => 'Mostrar Automaticamente',
        'create_visibility_option_manual' => 'Decidir Manualmente',
        'create_show_time_label' => 'A que horas deve ser mostrada a frase?',

        'create_create_button' => 'Criar',
        'create_cancel_button' => 'Cancelar',

        'create_content_length_error' => 'O conteúdo da frase não pode exceder 56 caracteres.',
        'create_show_time_past_error' => 'A data de visualização deve ser no futuro.',

        // Edit Page
        'edit_page_title' => 'Editar Frase',

        'edit_title_label' => 'Título da Frase:',
        'edit_content_label' => 'Conteúdo da Frase:',

        'edit_save_button' => 'Guardar',
        'edit_cancel_button' => 'Cancelar',

        'edit_error_occurred' => 'Ocorreu um erro ao atualizar a frase. Por favor, tente novamente mais tarde.',

        // Share Page
        'share_page_title' => 'Partilhar Frase',

        'share_use_url_label' => 'Utilize o seguinte URL para partilhar a frase:',
        'share_visibility_note' => 'Precisa de tornar a visibilidade pública para que as pessoas possam ver a sua frase.',

        'share_loading_text' => 'A carregar...',
        'share_see_phrase_button' => 'Ver a sua frase',

        // View Page
        'view_page_title' => 'Ver Frase',

        'view_created_label' => 'Criado',
        'view_visibility_label' => 'Visibilidade',
        'view_visibility_type_label' => 'Tipo de Visibilidade',
        'view_written_by_label' => 'Escrito por',

        'view_status_private' => 'Privado',
        'view_status_public' => 'Público',
        'view_status_waiting' => 'À espera de publicação',

        'view_phrase_not_published' => "O autor ainda não publicou a frase.",
        'view_phrase_not_yet_published' => "A frase ainda não está publicada. Tempo restante: ",

        // Admin Dashboard Page
        'admin_dashboard_page_title' => 'Dashboard do Admin',

        'admin_dashboard_users_title' => 'Visão Geral de Utilizadores',
        'admin_dashboard_users_explanation' => 'Está a ver toda a atividade dos utilizadores.',

        'admin_dashboard_phrases_title' => 'Visão Geral de Frases',
        'admin_dashboard_phrases_explanation' => 'Está a ver toda a atividade das frases.',

        'admin_dashboard_click' => 'Clique ',
        'admin_dashboard_here' => 'aqui',
        'admin_dashboard_to_manage_users' => 'para os gerir.',
        'admin_dashboard_to_manage_phrases' => 'para as gerir.',

        'admin_dashboard_filter_apply' => 'Aplicar',

        'admin_dashboard_users_no_users_found' => 'Nenhum Utilizador encontrado.',
        'admin_dashboard_users_no_users_explanation' => 'Você ainda não criou nenhum Utilizador.',

        'admin_dashboard_users_information' => 'Você está vendo as atividades dos usuários nos últimos 7 dias.',
        'admin_dashboard_phrases_information' => 'Você está vendo as atividades das frases nos últimos 7 dias.',

        // Admin Users Page
        'admin_page_title' => 'Admin',
        'admin_users_page_title' => 'Gerenciar Utilizadors',

        'admin_users_no_users_found' => 'Nenhum Utilizador encontrado.',
        'admin_users_no_users_explanation' => 'Você ainda não tem nenhum Utilizador criado.',

        'admin_users_user_id' => 'ID',
        'admin_users_user_name' => 'Nome',
        'admin_users_user_email' => 'Email',
        'admin_users_user_email_confirmed' => 'Email Confirmado',
        'admin_users_user_signup_time' => 'Data de Cadastro',
        'admin_users_user_status' => 'Status',

        'admin_users_view_user' => 'Visualizar Utilizador',
        'admin_users_edit_user' => 'Editar Utilizador',
        'admin_users_block_user' => 'Bloquear Utilizador',
        'admin_users_unblock_user' => 'Desbloquear Utilizador',
        'admin_users_delete_user' => 'Deletar Utilizador',
        'admin_users_delete_user_confirmation' => 'Tem certeza de que deseja deletar este Utilizador?',

        // Admin Users View Page
        'admin_user_view_page_title' => 'Visualizar Utilizador',

        'admin_user_view_id_label' => 'ID',
        'admin_user_view_email_confirmed_label' => 'Email Confirmado',
        'admin_user_view_confirmed' => 'Verdadeiro',
        'admin_user_view_not_confirmed' => 'Falso',
        'admin_user_view_signup_time_label' => 'Data de Cadastro',
        'admin_user_view_status_label' => 'Status',
        'admin_user_view_status_active' => 'Ativo',
        'admin_user_view_status_blocked' => 'Bloqueado',
        'admin_user_view_phrases_creaeted_label' => 'Frases Criadas',

        // Admin Users Edit Page
        'admin_user_edit_page_title' => 'Editar Utilizador',

        'admin_user_edit_username_label' => 'Nome de Utilizador',
        'admin_user_edit_email_label' => 'Email',
        'admin_user_edit_status_label' => 'Estado',
        'admin_user_edit_status_active' => 'Ativo',
        'admin_user_edit_status_blocked' => 'Bloqueado',

        'admin_user_edit_block_user' => 'Bloquear Utilizador',
        'admin_user_edit_unblock_user' => 'Desbloquear Utilizador',
        'admin_user_edit_delete_user' => 'Eliminar Utilizador',
        'admin_user_edit_delete_user_confirmation' => 'Tem a certeza de que quer eliminar este Utilizador?',

        // Admin Phrases Page
        'admin_phrases_page_title' => 'Gerenciar Frases',

        'admin_phrases_no_phrases_found' => 'Nenhuma Frase encontrada.',
        'admin_phrases_no_phrases_explanation' => 'Você ainda não criou nenhuma Frase.',

        'admin_phrases_phrase_id' => 'ID',
        'admin_phrases_phrase_title' => 'Título',
        'admin_phrases_phrase_content' => 'Conteúdo',
        'admin_phrases_phrase_status' => 'Status',
        'admin_phrases_phrase_visibility' => 'Visibilidade',
        'admin_phrases_phrase_visibility_private' => 'Privada',
        'admin_phrases_phrase_visibility_public' => 'Pública',
        'admin_phrases_phrase_visibility_waiting' => 'À espera de publicação',
        'admin_phrases_phrase_creation_date' => 'Data de Criação',

        'admin_phrases_view_phrase' => 'Visualizar Frase',
        'admin_phrases_edit_phrase' => 'Editar Frase',
        'admin_phrases_show_phrase' => 'Mostrar Frase',
        'admin_phrases_hide_phrase' => 'Esconder Frase',
        'admin_phrases_delete_phrase' => 'Eliminar Frase',
        'admin_phrases_delete_phrase_confirmation' => 'Tem a certeza de que quer eliminar esta Frase?',
    ],
];
