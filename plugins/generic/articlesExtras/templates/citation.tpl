{**
 * citation.tpl
 *
 * Copyright (c) 2009 Richard González Alberto
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Vancouver Citation Template
 *
 *}

{if $citation->getBookChapter()}{$citation->getBookChapter()}. {translate key="plugins.generic.articlesExtras.citation.in"}: {/if}
{if $citation->getConferenceArticle()}{$citation->getConferenceArticle()}. {translate key="plugins.generic.articlesExtras.citation.in"}: {/if}
{if $citation->getAuthors()}{$citation->getAuthors()}.{/if}
{if !$citation->getAuthors()}{if $citation->getEditors()}{$citation->getEditors()}, {translate key="plugins.generic.articlesExtras.citation.editors"}.{/if}{/if}
{if $citation->getTitle()} {$citation->getTitle()}{if $citation->getTypeTitle()} [{$citation->getTypeTitle()}]{/if}.{/if}
{if $citation->getConference()} {$citation->getConference()}{/if}
{if $citation->getSource()} {$citation->getSource()}{if $citation->getTypeSource()} [{$citation->getTypeSource()}]{/if}.{/if}
{if $citation->getEdition()} {$citation->getEdition()} ed.{/if}
{if $citation->getAuthors()}{if $citation->getEditors()}{$citation->getEditors()}, {translate key="plugins.generic.articlesExtras.citation.editors"}.{/if}{/if}
{if $citation->getPubPlace()} {$citation->getPubPlace()}{if $citation->getState()} ({$citation->getState()}){/if}{if $citation->getEditorial()}:{else}.{/if}{/if} 
{if $citation->getEditorial()}{$citation->getEditorial()}; {/if}
{if $citation->hasDate()}{$citation->getDate()}{if $citation->isMonograph()}.{/if}{/if}
{if $citation->hasWebsiteDate()}c{$citation->getWebsiteDate()} {/if}
{if $citation->hasCitationDate() || $citation->hasLastUpdateDate()}[
{if $citation->hasLastUpdateDate()}{translate key="plugins.generic.articlesExtras.citation.updated"} {$citation->getLastUpdateDate()}{/if}
{if $citation->hasCitationDate() && $citation->hasLastUpdateDate()}; {/if}
{if $citation->hasCitationDate()}{translate key="plugins.generic.articlesExtras.citation.cited"} {$citation->getCitationDate()}{/if}
]{/if}
{if $citation->getSitePage()}. {$citation->getSitePage()};{/if}
{if $citation->getVolume()}; {$citation->getVolume()}{if $citation->getVolumeSuppl()} Suppl {$citation->getVolumeSuppl()}{/if}{if $citation->getVolumePart()}(Pt {$citation->getVolumePart()}){/if}{if !$citation->getIssue()}:{/if}{/if}
{if $citation->getIssue()}({$citation->getIssue()}{if $citation->getIssueSuppl()} Suppl {$citation->getIssueSuppl()}{/if}{if $citation->getIssuePart()} Pt {$citation->getIssuePart()}{/if}):{/if}
{if $citation->getPages()}{if $citation->getVolumeSuppl() || $citation->getIssueSuppl()}S{/if}{if $citation->isMonograph()} p. {/if}{$citation->getPages()}.{/if}
{if $citation->getPageCount()} [aprox. {$citation->getPageCount()}p].{/if}
{if $citation->isRetraction()}. {translate key="plugins.generic.articlesExtras.citation.retraction"} {if $citation->isRetractionOf()}{translate key="plugins.generic.articlesExtras.citation.of}:{/if}{if $citation->isRetractionIn()}{translate key="plugins.generic.articlesExtras.citation.in}:{/if} {$citation->getRetraction()}.{/if}
{if $citation->isCorrection()}. {translate key="plugins.generic.articlesExtras.citation.corrected_republished_from"}: {$citation->getCorrection()}{/if}
{if $citation->isErratum()}; {translate key="plugins.generic.articlesExtras.citation.discussion"} {$citation->getDiscussion()}. Erratum in: {$citation->getErratum()}{/if}
{if $citation->getSection()};{translate key="plugins.generic.articlesExtras.citation.sect"}. {$citation->getSection()}{if $citation->getColumn()} ({$citation->getColumn()}){/if}.{/if}
{if $citation->getUrl()} {translate key="plugins.generic.articlesExtras.citation.availablefrom"}: {$citation->getUrl()}.{/if}
{if $citation->getForthcomingDate()} {translate key="plugins.generic.articlesExtras.citation.forthcoming"} {$citation->getForthcomingDate()}.{/if}