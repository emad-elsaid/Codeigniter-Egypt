/*
 * See ../license.txt for licensing
 *
 * For further information visit:
 * 	http://kfm.verens.com/
 *
 * File Name: ga.js
 * 	Irish language file.
 *
 * File Authors:
 * 	Kae Verens:     kae@verens.com
 *  Ed Galligan:    ed.galligan@gmail.com (many fixes)
 *  Kevin Scannell: kscanne@gmail.com (hints and grammatical software)
 */

kfm.lang=
{
Dir:
	"ltr", // language direction
ErrorPrefix:
	"earráid: ",
// what you see on the main page
Directories:
	"Comhadlannaí",
CurrentWorkingDir:
	"Comhadlann Oibre: \"%1\"",
Logs:
	"Loganna",
FileUpload:
	"Uasluchtaigh Comhad",
DirEmpty:
	"níl aon chomhaid in \"%1\"",

// right click menu item directory
// directory
CreateSubDir:
	"cruthaigh fochomhadlann",
DeleteDir:
	"scrios",
RenameDir:
	"athainmnigh",

// file
DeleteFile:
	"scrios",
RenameFile:
	"athainmnigh",
RotateClockwise:
	"rothlaigh deiseal",
RotateAntiClockwise:
	"rothlaigh tuathal",
ResizeImage:
	"athraigh tomhas an pictiúr",
ChangeCaption:
	"athraigh ceannteideal",

// create a file
WhatFilenameToCreateAs:
	"Cad is ainm den comhad?",
AskIfOverwrite:
	"Tá comhad leis an t-ainm \"%1\" ann cheana. Forscríobh?",
NoForwardslash:
	"\nNíl cead '/' a úsáid in ainm comhad",

// messages management
CreateDirMessage:
	"Cruthaigh focomhadlann i \"%1\":",
DelDirMessage:
	"An bhfuil tú cinnte gur mian leat an comhadlann \"%1\" a scrios?",
DelFileMessage:
	"An bhfuil tú cinnte gur mian leat an comhad \"%1\" a scrios?",
DelMultipleFilesMessage:
	"An bhfuil tú cinnte gur mian leat na comhaid seo a scrios?\n\n'",
DownloadFileFromMessage:
	"Íosluchtaigh ó cén áit?",
FileSavedAsMessage:
	"Cad is mian leat an comhad a ainmniú?",

// resize file
CurrentSize:
	"Tomhas Reatha: \"%1\" x \"%2\"\n",
NewWidth:
	"Leithead Nua?",
NewWidthConfirmTxt:
	"Leithead Nua: \"%1\"\n",
NewHeight:
	"Airde Nua?",
NewHeightConfirmTxt:
	"Airde Nua: \"%1\"\n\nAn bhfuil seo ceart?",

// log messages
RenamedFile:
	"ag athainmniú ó \"%1\" go \"%2\".",
DirRefreshed:
	"comhadlannaí úraighte.",
FilesRefreshed:
	"comhaidí úraighte.",
NotMoreThanOneFile:
	"earráid: ní féidir ach comhad amháin a roghnú ag am amháin",
UnknownPanelState:
	"earráid: stáit painéil nach bhfuil fios faoi.",
SetStylesError:
	"earráid: ní féidir \"%1\" a chuir mar \"%2\.",
NoPanel:
	"earráid: níl painéil \"%1\" ann.",
FileSelected:
	"comhad roghnaithe: \"%1\"",
Log_ChangeCaption:
	"ag aithrú ceannteideal ó \"%1\" go \"%2\"",
UrlNotValidLog:
	"earráid: caithfidh an URL tosú le \"http:\"",
MovingFilesTo:
	"ag aistriú chomhaid [\"%1\"] go \"%2\"",

// error messages
DirectoryNameExists:
	"tá comhadlann leis an t-ainm sin ann cheana.",
FileNameNotAllowd:
	"earráid: níl cead an comhad-ainm sin",
CouldNotWriteFile:
	"earráid: ní bhféidir an comhad \"%1\" a cruthú.",
CouldNotRemoveDir:
	"ní bhféidir an comhadlann a chealú.\ncaithfidh sé a bheith folamh",
UrlNotValid:
	"earráid: caithfidh an URL tosaidh le \"http:\"",
CouldNotDownloadFile:
	"earráid: ní féidir an comhad \"%1\" a íosluchtaigh.",
FileTooLargeForThumb:
	"earráid: tá \"%1\" rómhór chun thumbnail a cruthaigh. Caithfidh comhad níos beag a úsáid.",
CouldntReadDir:
	"earráid: ní féidir an chomhadlann a léamh",
CannotRenameFile:
	"earráid: ní féidir \"%1\" a ainmniú mar \"%2\"",
FilenameAlreadyExists:
	"earráid: tá comhad leis an ainm sin ann cheana",

// new in 0.5
EditTextFile:
	"scríobh an téacs-chomhad",
CloseWithoutSavingQuestion:
	"An bhfuil tú cinnte gur mian leat an comhad a dhúnadh gan é a shábháil?",
CloseWithoutSaving:
	"Dún Gan Sábháil",
SaveThenClose:
	"Sábháil, agus Dún",
SaveThenCloseQuestion:
	"An bhfuil tú cinnte gur mian leat na hathruithe a shábháil?",

// new in 0.6
LockPanels:
	"cuir glas ar na bpainéil",
UnlockPanels:
	"scaoil na painéil",
CreateEmptyFile:
	"cruthaigh comhad folamh",
DownloadFileFromUrl:
	"Íosluchtaigh ó URL",
DirectoryProperties:
	"Sonraí an Chomhadlann",
SelectAll:
	"roghnaigh gach rud",
SelectNone:
	"roghnaigh dada",
InvertSelection:
	"iompaigh an rogha",
LoadingKFM:
	"ag luchtú KFM",
Name:
	"ainm",
FileDetails:
	"Sonraí an Chomhad",
Search:
	"Cuardaigh",
IllegalDirectoryName:
	"níl cead comhadlann leis an t-ainm \"%1\"",
RecursiveDeleteWarning:
	"níl \"%1\" folamh\nAn bhfuil tú cinnte gur mhaith leat é agus a hábhair a scriosadh?\n*RABHADH* NÍ FÉIDIR É SEO A FHREASCHUR",
RmdirFailed:
	"theip ar scriosadh an comhadlann \"%1\"",
DirNotInDb:
	"níl an chomhadlann sa bhunachar sonraí",
ShowPanel:
	"taispeáin an phainéil \"%1\"",
ChangeCaption:
	"Athraigh an Cheannteideal",
NewDirectory:
	"Comhadlann Nua",
Upload:
	"Uasluchtaigh Comhad",
NewCaptionIsThisCorrect:
	"Ceannteideal Nua:\n%1\n\nAn bhfuil seo ceart?",
Close:
	"dún",
Loading:
	"ag luchtú",
AreYouSureYouWantToCloseKFM:
	"An bhfuil tú cinnte gur mhaith leat KFM a dhúnadh?",
PleaseSelectFileBeforeRename:
	"Roghnaigh comhad roimhe a athainmniú",
RenameOnlyOneFile:
	"Ní féidir ach comhad amháin a athainmniú ag aon am",
RenameFileToWhat:
	"Céard is mian leat \"%1\" a athainmniú?",
NoRestrictions:
	"gan srianta",
Filename:
	"ainm an comhad",
Maximise:
	"uastaigh",
Minimise:
	"íostaigh",
AllowedFileExtensions:
	"aicmí comhad ceadaithe",
Filesize:
	"tomhas an comhad",
MoveDown:
	"bog síos",
Mimetype:
	"aicmí MIME",
MoveUp:
	"bog suas",
Restore:
	"athbhunaigh",
Caption:
	"ceannteideal",
CopyFromURL:
	"Athscríobh ó URL",
ExtractZippedFile:
	"Bain as comhad Zip",

// new in 0.8
ViewImage:
	"breathnaigh ar pictiúr",
ReturnThumbnailToOpener:
	"tabhair mionsamhail ar ais don oscailaitheoir",
AddTagsToFiles:
	"cur clibeanna le comhad(anna)",
RemoveTagsFromFiles:
	"tóg clibeanna ó comhad(anna)",
HowWouldYouLikeToRenameTheseFiles:
	"Conas ar mhaith leat na chomhaid a athainmniú?\n\ne.g.: ó \"images-***.jpg\" bainfidh tú \"images-001.jpg\", \"images-002.jpg\", ...",
YouMustPlaceTheWildcard:
	"Caithfidh leat an litir * a chur sa teimpléad",
YouNeedMoreThan:
	"Caithfidh níos mó ná %1 * litreacha a úsáid chun %2 ainm comhaid a dhéanamh",
NoFilesSelected:
	"níl aon chomhaid roghnaithe",
Tags:
	"clibeanna",
IfYouUseMultipleWildcards:
	"Má tá iolraí * úsáidte sa teimpléad, caithfidh na * a bheith le chéile",
NewCaption:
	"Ceannteideal Nua",
WhatMaximumSize:
	"Cén tomhas is mó is mian leat a chur ar ais?",
CommaSeparated:
	"scartha le camóig",
WhatIsTheNewTag:
	"Céard é an clib nua?\nLe clibeanna iolraithe a úsáid, idirscar le camóg.",
WhichTagsDoYouWantToRemove:
	"Cén clibeanna is mian leat a scriosadh?\nLe clibeanna iolraithe a scriosadh, idirscar le camóg."

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
