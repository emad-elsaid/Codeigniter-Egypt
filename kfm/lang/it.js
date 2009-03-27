/*
 * See ../license.txt for licensing
 *
 * For further information visit:
 * 	http://kfm.verens.com/
 *
 * File Name: it.js
 * 	Italian language file.
 *
 * File Authors:
 * 	stefano.luchetta@consiglio.marche.it
 */

kfm.lang=
{
Dir:
	"ltr", // language direction
ErrorPrefix:
	"Errore: ",
// what you see on the main page
Directories:
	"Cartelle",
CurrentWorkingDir:
	"Cartella corrente: \"%1\"",
Logs:
	"Registro operazioni",
FileUpload:
	"Carica file",
DirEmpty:
	"non ci sono file nella cartella \"%1\"",

// right click menu item directory
// directory
CreateSubDir:
	"crea una sotto cartella",
DeleteDir:
	"elimina",
RenameDir:
	"rinomina",

// file
DeleteFile:
	"elimina",
RenameFile:
	"rinomina",
RotateClockwise:
	"ruota in senso orario",
RotateAntiClockwise:
	"ruota in senso antiorario",
ResizeImage:
	"ridimensiona immagine",
ChangeCaption:
	"cambia descrizione",

// create a file
WhatFilenameToCreateAs:
	"In che formato deve essere creato il file?",
AskIfOverwrite:
	"Il file \"%1\" esiste. Sovrascriverlo?",
NoForwardslash:
	"\nNon puoi usare '/' nel nome del file",

// messages management
CreateDirMessage:
	"Crea una sottocartella di \"%1\":",
DelDirMessage:
	"Vuoi cancellare la cartella \"%1\"?",
DelFileMessage:
	"Vuoi cancellare la cartella \"%1\"?",
DelMultipleFilesMessage:
	"Vuoi cancellare questi file?\n\n'",
DownloadFileFromMessage:
	"URL:",
FileSavedAsMessage:
	"In che formato deve essere salvato il file?",

// resize file
CurrentSize:
	"Misure attuali: \"%1\" x \"%2\"\n",
NewWidth:
	"Nuova larghezza?",
NewWidthConfirmTxt:
	"Nuova larghezza: \"%1\"\n",
NewHeight:
	"Nuova altezza?",
NewHeightConfirmTxt:
	"Nuova altezza: \"%1\"\n\n dimensioni corrette?",

// log messages
RenamedFile:
	"Rinomino il file \"%1\" a \"%2\".",
DirRefreshed:
	"Visualizzazione cartelle aggiornata.",
FilesRefreshed:
	"Visualizazzione file aggiornata.",
NotMoreThanOneFile:
	"errore: non puoi sceglierne piu\' di uno alla volta",
UnknownPanelState:
	"errore: stato del pannello sconosciuto.",
// MissingDirWrapper:
// 	"error: missing directory wrapper: \"kfm_directories%1\".",
SetStylesError:
	"errore: impossibile impostare \"%1\" a \"%2\.",
NoPanel:
	"error: il pannello \"%1\" non esiste.",
FileSelected:
	"file selezionato: \"%1\"",
Log_ChangeCaption:
	"cambio descrizione di \"%1\" a \"%2\"",
UrlNotValidLog:
	"errore: la URL deve iniziare per \"http:\"",
MovingFilesTo:
	"sposto i file da [\"%1\"] a \"%2\"",

// error messages
DirectoryNameExists:
	"una cartella con questo nome esiste già.",
FileNameNotAllowd:
	"errore: il nome del file non è corretto",
CouldNotWriteFile:
	"errore: non posso scrivere il file \"%1\".",
CouldNotRemoveDir:
	"non posso eliminare la cartella. Assicurarsi che sia vuota",
UrlNotValid:
	"errore: la URL deve iniziare con \"http:\"",
CouldNotDownloadFile:
	"errore: non posso scaricare il file \"%1\".",
FileTooLargeForThumb:
	"errore: \"%1\" è troppo grande per creare una anteprima. Diminuire le dimensioni del file.",
CouldntReadDir:
	"errore: non posso leggere la cartella",
CannotRenameFile:
	"errore: non posso rinominare \"%1\" a \"%2\"",
FilenameAlreadyExists:
	"errore: un file con questo nome esiste già",

// new in 0.5
EditTextFile:
	"edita un file di testo",
CloseWithoutSavingQuestion:
	"Uscire senza salvare?",
CloseWithoutSaving:
	"Chiudi senza salvare",
SaveThenClose:
	"Salva, poi chiudi",
SaveThenCloseQuestion:
	"Salvare le modifiche?",

// new in 0.6
LockPanels:
	"Blocca i pannelli",
UnlockPanels:
	"Sblocca i pannelli",
CreateEmptyFile:
	"Crea un file vuoto",
DownloadFileFromUrl:
	"Carica file da URL",
DirectoryProperties:
	"Proprietà cartella",
SelectAll:
	"seleziona tutto",
SelectNone:
	"deseleziona",
InvertSelection:
	"inverti selezione",
LoadingKFM:
	"Carico KFM",
Name:
	"nome",
FileDetails:
	"Proprietà file",
Search:
	"Cerca",
IllegalDirectoryName:
	"nome cartella non corretto: \"%1\"",
RecursiveDeleteWarning:
	"\"%1\" non è vuota\nCancellarla con tutto il suo contenuto?\n*ATTENZIONE* NON SARA\' PIU\' POSSIBILE RIPRISTINARLA",
RmdirFailed:
	"impossibile eliminare la cartella \"%1\"",
DirNotInDb:
	"la cartella non è nel database",
ShowPanel:
	"mostra pannello \"%1\"",
ChangeCaption:
	"cambia descrizione",
NewDirectory:
	"Nuova cartella",
Upload:
	"Carica",
NewCaptionIsThisCorrect:
	"Nuova descrizione:\n%1\n\nE\' corretta?",
Close:
	"chiudi",
Loading:
	"caricamento",
AreYouSureYouWantToCloseKFM:
	"Chiudere la finestra KFM?",
PleaseSelectFileBeforeRename:
	"seleziona un file!",
RenameOnlyOneFile:
	"Puoi rinominare solo un file alla volta",
RenameFileToWhat:
	"Occorre specificare il nuovo nome del file \"%1\"!",
NoRestrictions:
	"nessuna restrizione",
Filename:
	"nome file",
Maximise:
	"massimizza",
Minimise:
	"minimizza",
AllowedFileExtensions:
	"estensioni file consentite",
Filesize:
	"dimensione file",
MoveDown:
	"sposta giu\'",
Mimetype:
	"mimetype",
MoveUp:
	"sposta su\'",
Restore:
	"ripristina",
Caption:
	"descrizione",
CopyFromURL:
	"Copy from URL",
ExtractZippedFile:
	"Extract zipped file",

// new in 0.8
ViewImage:
	"view image",
ReturnThumbnailToOpener:
	"return thumbnail to opener",
AddTagsToFiles:
	"add tags to file(s)",
RemoveTagsFromFiles:
	"remove tags from file(s)",
HowWouldYouLikeToRenameTheseFiles:
	"How would you like to rename these files?\n\nexample: \"images-***.jpg\" will rename files to \"images-001.jpg\", \"images-002.jpg\", ...",
YouMustPlaceTheWildcard:
	"You must place the wildcard character * somewhere in the filename template",
YouNeedMoreThan:
	"You need more than %1 * characters to create %2 filenames",
NoFilesSelected:
	"no files selected",
Tags:
	"tags",
IfYouUseMultipleWildcards:
	"If you use multiple wildcards in the filename template, they must be grouped together",
NewCaption:
	"New Caption",
WhatMaximumSize:
	"What maximum size should be returned?",
CommaSeparated:
	"comma-separated",
WhatIsTheNewTag:
	"What is the new tag?\nFor multiple tags, separate by commas.",
WhichTagsDoYouWantToRemove:
	"Which tags do you want to remove?\nFor multiple tags, separate by commas."

,
// New in 0.9
AllFiles: "all files",
AndNMore: "...and %1 more...",
Browse: "Browse...",
ExtractAfterUpload: "extract after upload",
NotAnImageOrImageDimensionsNotReported: "error: not an image, or image dimensions not reported",
PermissionDeniedCannotDeleteFile: "permission denied: cannot delete file",
RenameTheDirectoryToWhat: "Rename the directory '%1' to what?",
RenamedDirectoryAs: "Renamed '%1' as '%2'",
TheFilenameShouldEndWithN: "The filename should end with %1",
WhatFilenameDoYouWantToUse: "What filename do you want to use?"

,
// New in 1.0
ZipUpFiles: "zip up files",
Cancel: "cancel"
	, // new in 1.2
	Icons                   : "icons", // used to select mode of file view
	ListView                : "list-view", // used to select mode of file view
	SendToCms               : "send to CMS", // close KFM and return the selected files to the CMS
	CannotMoveDirectory     : "permission denied: cannot move directory",
	LastModified            : "last modified", // part of File Details
	ImageDimensions         : "image dimensions", // part of File Details
	CouldNotMoveFiles       : "error: could not move file[s]",
	CopyFiles               : "copy files", // when dragging files to a directory, two choices appear - "copy files" and "move files"
	MoveFiles               : "move files",
	AboutKfm                : "about KFM",
	Errors                  : "Errors",
	Ok                      : "OK" // as in "OK / Cancel"
}
