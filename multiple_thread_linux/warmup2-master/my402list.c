#include "my402list.h"
#include<stdlib.h>
#include<stdio.h>

//Insert obj between two nodes.
int My402ListInsert(void *obj, My402ListElem *new, My402ListElem *prev, My402ListElem *next)
{
    new->obj = obj;

    prev->next = new;
    new->prev = prev;
    new->next = next;
    next->prev = new;
    
    return TRUE;
}

//Returns the number of elements in the list.
int My402ListLength(My402List *header)
{
    return header->num_members;        
}

//Returns TRUE if the list is empty. Returns FALSE otherwise.
int My402ListEmpty(My402List *header)
{
    return (header->num_members == 0);
}

//Add obj after last
int My402ListAppend(My402List *header, void *obj)
{
    My402ListElem *tmp;
    
    tmp = (My402ListElem *)malloc(sizeof(My402ListElem));
    if (tmp == NULL) /*Make sure malloc() works well*/
    {
        printf("Out of space!\n");
        return FALSE;
    }

    return (My402ListInsert(obj, tmp, header->anchor.prev, &header->anchor) && ++header->num_members);
   
    /*
    tmp->obj = obj;
    tmp->next = &header->anchor; 
    tmp->prev = header->anchor.prev;

    header->anchor.prev->next = tmp;
    header->anchor.prev = tmp;
    
    header->num_members++;

    return TRUE;
    */
}

/*Add obj before First*/
int My402ListPrepend(My402List *header, void *obj)
{
    My402ListElem *tmp;
    
    tmp = (My402ListElem *)malloc(sizeof(My402ListElem));
    if (tmp == NULL) /*Make sure malloc() works well*/
    {
        printf("Out of space!\n");
        return FALSE;
    }

    return (My402ListInsert(obj, tmp, &header->anchor, header->anchor.next) && ++header->num_members);
    /*
    tmp->obj = obj;
    tmp->next = first; 
    tmp->prev = first->prev;
    first->prev = ;
    list->anchor.prev = ;
    */
}

void My402ListUnlink(My402List *header, My402ListElem *elem)
{
    if ((elem != &header->anchor) && My402ListLength(header)) 
    {
        elem->prev->next = elem->next;
        elem->next->prev = elem->prev;
        
        elem->prev = NULL;
        elem->next = NULL;
        --header->num_members;
        free(elem);
    }
}

void My402ListUnlinkAll(My402List *header)
{
    My402ListElem *tmp, *pos;
    pos = header->anchor.next;
    
    while (pos != &header->anchor)
    {
        tmp = pos->next;
        free(pos);
        pos = tmp;
    }
    
    header->num_members = 0;
    header->anchor.next = header->anchor.prev = &header->anchor;
}

int My402ListInsertAfter(My402List *header, void *obj, My402ListElem *elem)
{
    My402ListElem *tmp;

    if (elem == NULL)
    {
        return My402ListAppend(header, obj);
    }

    
    tmp = (My402ListElem *)malloc(sizeof(My402ListElem));
    if (tmp == NULL) /*Make sure malloc() works well*/
    {
        printf("Out of space!\n");
        return FALSE;
    }

    return (My402ListInsert(obj, tmp, elem, elem->next) && ++header->num_members);
}

int My402ListInsertBefore(My402List *header, void *obj, My402ListElem *elem)
{

    My402ListElem *tmp;

    if (elem == NULL)
    {
        return My402ListPrepend(header, obj);
    }

    
    tmp = (My402ListElem *)malloc(sizeof(My402ListElem));
    if (tmp == NULL) /*Make sure malloc() works well*/
    {
        printf("Out of space!\n");
        return FALSE;
    }

    return (My402ListInsert(obj, tmp, elem->prev, elem) && ++header->num_members);
}

My402ListElem *My402ListFirst(My402List *header)
{
    if (!My402ListEmpty(header))
    {
        return header->anchor.next;
    }
    else
    {
        return NULL;
    }
}

My402ListElem *My402ListLast(My402List *header)
{
    if (!My402ListEmpty(header))
    {
        return header->anchor.prev;
    }
    else
    {
        return NULL;
    }
}

My402ListElem *My402ListNext(My402List *header, My402ListElem *elem)
{
    if (elem->next == &header->anchor)
    {
        return NULL;
    }
    else
    {
        return elem->next;
    }
}

My402ListElem *My402ListPrev(My402List *header, My402ListElem *elem)
{
    if (elem->prev == &header->anchor)
    {
        return NULL;
    }
    else
    {
        return elem->prev;
    }
}

My402ListElem *My402ListFind(My402List *header, void *obj)
{
    My402ListElem *pos;
    pos = header->anchor.next;

    while (pos != &header->anchor)
    {
        if (pos->obj == obj)
        {
            return pos;
        }
        else
        {
            pos = pos->next;
        }
    }
    
    return NULL;
}

int My402ListInit(My402List *header)
{
    /*
    header = (My402List *)malloc(sizeof(My402List));
    if (header == NULL)
    {
        printf("Out of space!\n");
        return FALSE;
    }
    */

    header->num_members = 0;
    header->anchor.next = &header->anchor;
    header->anchor.prev = &header->anchor;
    header->anchor.obj = NULL;
    return TRUE;
}
